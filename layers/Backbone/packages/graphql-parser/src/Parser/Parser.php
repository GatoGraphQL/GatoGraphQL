<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PoPBackbone\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoPBackbone\GraphQLParser\Parser\Ast\AbstractAst;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputList;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\InputObject;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\VariableReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Ast\Field;
use PoPBackbone\GraphQLParser\Parser\Ast\Fragment;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\Mutation;
use PoPBackbone\GraphQLParser\Parser\Ast\Query;
use PoPBackbone\GraphQLParser\Parser\Ast\TypedFragmentReference;
use PoPBackbone\GraphQLParser\Parser\Ast\WithDirectivesInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\WithValueInterface;
use stdClass;

class Parser extends Tokenizer implements ParserInterface
{
    /**
     * @var array<string,mixed>
     */
    private array $data = [];

    public function parse(string $source): ParsedData
    {
        $this->init($source);

        while (!$this->end()) {
            $tokenType = $this->peek()->getType();

            switch ($tokenType) {
                case Token::TYPE_LBRACE:
                    foreach ($this->parseBody(Token::TYPE_QUERY, true) as $query) {
                        $this->data['queries'][] = $query;
                    }
                    break;

                case Token::TYPE_QUERY:
                    [$operationName, $queries] = $this->parseOperation(Token::TYPE_QUERY);
                    $this->data['queryOperations'][] = [
                        'name' => $operationName,
                        'position' => count($this->data['queries']),
                        'numberItems' => count($queries),
                    ];
                    foreach ($queries as $query) {
                        $this->data['queries'][] = $query;
                    }
                    break;

                case Token::TYPE_MUTATION:
                    [$operationName, $mutations] = $this->parseOperation(Token::TYPE_MUTATION);
                    if ($operationName) {
                        $this->data['mutationOperations'][] = [
                            'name' => $operationName,
                            'position' => count($this->data['mutations']),
                            'numberItems' => count($mutations),
                        ];
                    }
                    foreach ($mutations as $query) {
                        $this->data['mutations'][] = $query;
                    }
                    break;

                case Token::TYPE_FRAGMENT:
                    $this->data['fragments'][] = $this->parseFragment();
                    break;

                default:
                    throw new SyntaxErrorException(
                        $this->getIncorrectRequestSyntaxErrorMessage(),
                        $this->getLocation()
                    );
            }
        }

        return new ParsedData(
            $this->data['queryOperations'],
            $this->data['mutationOperations'],
            $this->data['queries'],
            $this->data['mutations'],
            $this->data['fragments'],
            $this->data['fragmentReferences'],
            $this->data['variables'],
            $this->data['variableReferences'],
        );
    }

    protected function getIncorrectRequestSyntaxErrorMessage(): string
    {
        return 'Incorrect request syntax';
    }

    private function init(string $source): void
    {
        $this->initTokenizer($source);

        $this->data = [
            'queryOperations'    => [],
            'mutationOperations' => [],
            'queries'            => [],
            'mutations'          => [],
            'fragments'          => [],
            'fragmentReferences' => [],
            'variables'          => [],
            'variableReferences' => [],
        ];
    }

    /**
     * @return mixed[]
     */
    protected function parseOperation(string $type): array
    {
        $operation  = null;
        $directives = [];
        $operationName = null;

        if ($this->matchMulti([Token::TYPE_QUERY, Token::TYPE_MUTATION])) {
            $this->lex();

            $operationInfo = $this->eat(Token::TYPE_IDENTIFIER);
            if (!is_null($operationInfo)) {
                $operationName = $operationInfo->getData();
            }

            if ($this->match(Token::TYPE_LPAREN)) {
                $this->parseVariables();
            }

            if ($this->match(Token::TYPE_AT)) {
                $directives = $this->parseDirectiveList();
            }
        }

        $this->lex();

        $fields = [];

        while (!$this->match(Token::TYPE_RBRACE) && !$this->end()) {
            $this->eatMulti([Token::TYPE_COMMA]);

            /** @var WithDirectivesInterface */
            $operation = $this->parseBodyItem($type, true);
            $operation->setDirectives(
                array_merge(
                    $directives,
                    $operation->getDirectives()
                )
            );

            $fields[] = $operation;
        }

        $this->expect(Token::TYPE_RBRACE);

        return [
            $operationName,
            $fields,
        ];
    }

    /**
     * @return AbstractAst[]
     */
    protected function parseBody(string $token, bool $highLevel): array
    {
        $fields = [];

        $this->lex();

        while (!$this->match(Token::TYPE_RBRACE) && !$this->end()) {
            $this->eatMulti([Token::TYPE_COMMA]);

            if ($this->match(Token::TYPE_FRAGMENT_REFERENCE)) {
                $this->lex();

                if ($this->eat(Token::TYPE_ON)) {
                    $fields[] = $this->parseBodyItem(Token::TYPE_TYPED_FRAGMENT, $highLevel);
                } else {
                    $fields[] = $this->parseFragmentReference();
                }
            } else {
                $fields[] = $this->parseBodyItem($token, $highLevel);
            }
        }

        $this->expect(Token::TYPE_RBRACE);

        return $fields;
    }

    protected function parseVariables(): void
    {
        $this->eat(Token::TYPE_LPAREN);

        while (!$this->match(Token::TYPE_RPAREN) && !$this->end()) {
            $this->eat(Token::TYPE_COMMA);

            $variableToken = $this->eat(Token::TYPE_VARIABLE);
            $nameToken     = $this->eatIdentifierToken();
            $this->eat(Token::TYPE_COLON);

            $isArray              = false;
            $arrayElementNullable = true;

            if ($this->match(Token::TYPE_LSQUARE_BRACE)) {
                $isArray = true;

                $this->eat(Token::TYPE_LSQUARE_BRACE);
                $type = $this->eatIdentifierToken()->getData();

                if ($this->match(Token::TYPE_REQUIRED)) {
                    $arrayElementNullable = false;
                    $this->eat(Token::TYPE_REQUIRED);
                }

                $this->eat(Token::TYPE_RSQUARE_BRACE);
            } else {
                $type = $this->eatIdentifierToken()->getData();
            }

            $required = false;
            if ($this->match(Token::TYPE_REQUIRED)) {
                $required = true;
                $this->eat(Token::TYPE_REQUIRED);
            }

            $variable = $this->createVariable(
                (string)$nameToken->getData(),
                (string)$type,
                $required,
                $isArray,
                $arrayElementNullable,
                $this->getTokenLocation($variableToken),
            );

            if ($this->match(Token::TYPE_EQUAL)) {
                $this->eat(Token::TYPE_EQUAL);
                $variable->setDefaultValue($this->parseValue());
            }

            $this->data['variables'][] = $variable;
        }

        $this->expect(Token::TYPE_RPAREN);
    }

    protected function getTokenLocation(Token $token): Location
    {
        return new Location($token->getLine(), $token->getColumn());
    }

    protected function createVariable(
        string $name,
        string $type,
        bool $required,
        bool $isArray,
        bool $arrayElementNullable,
        Location $location,
    ): Variable {
        return new Variable(
            $name,
            $type,
            $required,
            $isArray,
            $arrayElementNullable,
            $location
        );
    }

    /**
     * @param string[] $types
     * @throws SyntaxErrorException
     */
    protected function expectMulti(array $types): Token
    {
        if ($this->matchMulti($types)) {
            return $this->lex();
        }

        throw $this->createUnexpectedException($this->peek());
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function parseVariableReference(): VariableReference
    {
        $startToken = $this->expectMulti([Token::TYPE_VARIABLE]);

        if ($this->match(Token::TYPE_NUMBER) || $this->match(Token::TYPE_IDENTIFIER) || $this->match(Token::TYPE_QUERY)) {
            $name = $this->lex()->getData();

            $variable = $this->findVariable($name);
            if ($variable) {
                $variable->setUsed(true);
            }

            $variableReference = $this->createVariableReference(
                $name,
                $variable,
                $this->getTokenLocation($startToken)
            );

            $this->data['variableReferences'][] = $variableReference;

            return $variableReference;
        }

        throw $this->createUnexpectedException($this->peek());
    }

    protected function createVariableReference(
        string $name,
        ?Variable $variable,
        Location $location,
    ): VariableReference {
        return new VariableReference($name, $variable, $location);
    }

    protected function findVariable(string $name): ?Variable
    {
        /** @var Variable[] */
        $variables = $this->data['variables'];
        foreach ($variables as $variable) {
            if ($variable->getName() === $name) {
                return $variable;
            }
        }

        return null;
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function parseFragmentReference(): FragmentReference
    {
        $nameToken         = $this->eatIdentifierToken();
        $fragmentReference = $this->createFragmentReference(
            $nameToken->getData(),
            $this->getTokenLocation($nameToken)
        );

        $this->data['fragmentReferences'][] = $fragmentReference;

        return $fragmentReference;
    }

    protected function createFragmentReference(
        string $name,
        Location $location,
    ): FragmentReference {
        return new FragmentReference($name, $location);
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function eatIdentifierToken(): Token
    {
        return $this->expectMulti([
            Token::TYPE_IDENTIFIER,
            Token::TYPE_MUTATION,
            Token::TYPE_QUERY,
            Token::TYPE_FRAGMENT,
        ]);
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function parseBodyItem(string $type, bool $highLevel): AbstractAst
    {
        $nameToken = $this->eatIdentifierToken();
        $alias     = null;

        if ($this->eat(Token::TYPE_COLON)) {
            $alias     = $nameToken->getData();
            $nameToken = $this->eatIdentifierToken();
        }

        $bodyLocation = $this->getTokenLocation($nameToken);
        $arguments    = $this->match(Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];
        $directives   = $this->match(Token::TYPE_AT) ? $this->parseDirectiveList() : [];

        if ($this->match(Token::TYPE_LBRACE)) {
            /** @var Query[] */
            $fields = $this->parseBody($type === Token::TYPE_TYPED_FRAGMENT ? Token::TYPE_QUERY : $type, false);

            if (!$fields) {
                throw $this->createUnexpectedTokenTypeException($this->lookAhead->getType());
            }

            if ($type === Token::TYPE_QUERY) {
                return $this->createQuery($nameToken->getData(), $alias, $arguments, $fields, $directives, $bodyLocation);
            }

            if ($type === Token::TYPE_TYPED_FRAGMENT) {
                return $this->createTypedFragmentReference($nameToken->getData(), $fields, $directives, $bodyLocation);
            }

            return $this->createMutation($nameToken->getData(), $alias, $arguments, $fields, $directives, $bodyLocation);
        }

        if ($highLevel && $type === Token::TYPE_MUTATION) {
            return $this->createMutation($nameToken->getData(), $alias, $arguments, [], $directives, $bodyLocation);
        }

        if ($highLevel && $type === Token::TYPE_QUERY) {
            return $this->createQuery($nameToken->getData(), $alias, $arguments, [], $directives, $bodyLocation);
        }

        return $this->createField($nameToken->getData(), $alias, $arguments, $directives, $bodyLocation);
    }

    /**
     * @param Argument[] $arguments
     * @param Field[]|Query[]|FragmentReference[]|TypedFragmentReference[] $fields
     * @param Directive[] $directives
     */
    protected function createQuery(
        string $name,
        ?string $alias,
        array $arguments,
        array $fields,
        array $directives,
        Location $location
    ): Query {
        return new Query(
            $name,
            $alias,
            $arguments,
            $fields,
            $directives,
            $location
        );
    }

    /**
     * @param Field[]|Query[] $fields
     * @param Directive[] $directives
     */
    protected function createTypedFragmentReference(
        string $typeName,
        array $fields,
        array $directives,
        Location $location,
    ): TypedFragmentReference {
        return new TypedFragmentReference($typeName, $fields, $directives, $location);
    }

    /**
     * @param Argument[] $arguments
     * @param Field[]|Query[]|FragmentReference[]|TypedFragmentReference[] $fields
     * @param Directive[] $directives
     */
    protected function createMutation(
        string $name,
        ?string $alias,
        array $arguments,
        array $fields,
        array $directives,
        Location $location,
    ): Mutation {
        return new Mutation($name, $alias, $arguments, $fields, $directives, $location);
    }

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    protected function createField(
        string $name,
        ?string $alias,
        array $arguments,
        array $directives,
        Location $location,
    ): Field {
        return new Field($name, $alias, $arguments, $directives, $location);
    }

    /**
     * @return Argument[]
     */
    protected function parseArgumentList(): array
    {
        $args = [];

        $this->expect(Token::TYPE_LPAREN);

        while (!$this->match(Token::TYPE_RPAREN) && !$this->end()) {
            $this->eat(Token::TYPE_COMMA);
            $args[] = $this->parseArgument();
        }

        $this->expect(Token::TYPE_RPAREN);

        return $args;
    }

    protected function parseArgument(): Argument
    {
        $nameToken = $this->eatIdentifierToken();
        $this->expect(Token::TYPE_COLON);
        $value = $this->parseValue();

        return $this->createArgument($nameToken->getData(), $value, $this->getTokenLocation($nameToken));
    }

    protected function createArgument(
        string $name,
        WithValueInterface $value,
        Location $location,
    ): Argument {
        return new Argument($name, $value, $location);
    }

    /**
     * @return Directive[]
     */
    protected function parseDirectiveList(): array
    {
        $directives = [];

        while ($this->match(Token::TYPE_AT)) {
            $directives[] = $this->parseDirective();
            $this->eat(Token::TYPE_COMMA);
        }

        return $directives;
    }

    protected function parseDirective(): Directive
    {
        $this->expect(Token::TYPE_AT);

        $nameToken = $this->eatIdentifierToken();
        $args      = $this->match(Token::TYPE_LPAREN) ? $this->parseArgumentList() : [];

        return $this->createDirective($nameToken->getData(), $args, $this->getTokenLocation($nameToken));
    }

    /**
     * @param Argument[] $arguments
     */
    protected function createDirective(
        $name,
        array $arguments,
        Location $location,
    ): Directive {
        return new Directive($name, $arguments, $location);
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function parseValue(): array|InputList|InputObject|Literal|VariableReference
    {
        switch ($this->lookAhead->getType()) {
            case Token::TYPE_LSQUARE_BRACE:
                return $this->parseList(true);

            case Token::TYPE_LBRACE:
                return $this->parseObject(true);

            case Token::TYPE_VARIABLE:
                return $this->parseVariableReference();

            case Token::TYPE_NUMBER:
            case Token::TYPE_STRING:
            case Token::TYPE_IDENTIFIER:
            case Token::TYPE_NULL:
            case Token::TYPE_TRUE:
            case Token::TYPE_FALSE:
                $token = $this->lex();

                return $this->createLiteral($token->getData(), $this->getTokenLocation($token));
        }

        throw $this->createUnexpectedException($this->lookAhead);
    }

    /**
     * @param string|int|float|bool|null $value
     */
    public function createLiteral(
        string|int|float|bool|null $value,
        Location $location
    ): Literal {
        return new Literal($value, $location);
    }

    protected function parseList(bool $createType): InputList|array
    {
        $startToken = $this->eat(Token::TYPE_LSQUARE_BRACE);

        $list = [];
        while (!$this->match(Token::TYPE_RSQUARE_BRACE) && !$this->end()) {
            $list[] = $this->parseListValue();

            $this->eat(Token::TYPE_COMMA);
        }

        $this->expect(Token::TYPE_RSQUARE_BRACE);

        return $createType ? $this->createInputList($list, $this->getTokenLocation($startToken)) : $list;
    }

    /**
     * @param mixed[] $list
     */
    protected function createInputList(
        array $list,
        Location $location,
    ): InputList {
        return new InputList($list, $location);
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function parseListValue(): mixed
    {
        return match ($this->lookAhead->getType()) {
            Token::TYPE_NUMBER,
            Token::TYPE_STRING,
            Token::TYPE_TRUE,
            Token::TYPE_FALSE,
            Token::TYPE_NULL,
            Token::TYPE_IDENTIFIER
                => $this->expect($this->lookAhead->getType())->getData(),
            Token::TYPE_VARIABLE
                => $this->parseVariableReference(),
            Token::TYPE_LBRACE
                => $this->parseObject(true),
            Token::TYPE_LSQUARE_BRACE
                => $this->parseList(false),
            default
                => throw new SyntaxErrorException(
                    $this->getCantParseArgumentErrorMessage(),
                    $this->getLocation()
                ),
        };
    }

    protected function getCantParseArgumentErrorMessage(): string
    {
        return 'Can\'t parse argument';
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function parseObject(bool $createType): InputObject|stdClass
    {
        $startToken = $this->eat(Token::TYPE_LBRACE);

        // Use stdClass instead of array
        $object = new stdClass();
        while (!$this->match(Token::TYPE_RBRACE) && !$this->end()) {
            $key = $this->expectMulti([Token::TYPE_STRING, Token::TYPE_IDENTIFIER])->getData();
            $this->expect(Token::TYPE_COLON);
            $value = $this->parseListValue();

            $this->eat(Token::TYPE_COMMA);

            $object->$key = $value;
        }

        $this->eat(Token::TYPE_RBRACE);

        return $createType ? $this->createInputObject($object, $this->getTokenLocation($startToken)) : $object;
    }

    protected function createInputObject(
        stdClass $object,
        Location $location,
    ): InputObject {
        return new InputObject($object, $location);
    }

    /**
     * @throws SyntaxErrorException
     */
    protected function parseFragment(): Fragment
    {
        $this->lex();
        $nameToken = $this->eatIdentifierToken();

        $this->eat(Token::TYPE_ON);

        $model = $this->eatIdentifierToken();

        $directives = $this->match(Token::TYPE_AT) ? $this->parseDirectiveList() : [];

        /** @var Query[] */
        $fields = $this->parseBody(Token::TYPE_QUERY, false);

        return $this->createFragment($nameToken->getData(), $model->getData(), $directives, $fields, $this->getTokenLocation($nameToken));
    }

    /**
     * @param Directive[] $directives
     * @param Field[]|Query[] $fields
     */
    protected function createFragment(
        string $name,
        string $model,
        array $directives,
        array $fields,
        Location $location,
    ): Fragment {
        return new Fragment($name, $model, $directives, $fields, $location);
    }

    protected function eat(string $type): ?Token
    {
        if ($this->match($type)) {
            return $this->lex();
        }

        return null;
    }

    /**
     * @param string[] $types
     */
    protected function eatMulti(array $types): ?Token
    {
        if ($this->matchMulti($types)) {
            return $this->lex();
        }

        return null;
    }

    /**
     * @param string[] $types
     */
    protected function matchMulti(array $types): bool
    {
        foreach ($types as $type) {
            if ($this->peek()->getType() === $type) {
                return true;
            }
        }

        return false;
    }
}
