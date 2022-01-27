<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser;

use PoP\Root\Services\BasicServiceTrait;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\Variable as ExtendedVariable;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\Directive as ExtendedDirective;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\Document;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\LeafField as ExtendedLeafField;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\RelationalField as ExtendedRelationalField;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\Spec\Parser\Parser as UpstreamParser;

class Parser extends UpstreamParser implements ParserInterface
{
    use BasicServiceTrait;

    protected function getIncorrectRequestSyntaxErrorMessage(?string $syntax): string
    {
        $errorMessage = $this->__('Incorrect request syntax', 'graphql-parser');
        if ($syntax === null) {
            return $errorMessage;
        }
        return \sprintf(
            $this->__('%s: \'%s\'', 'graphql-parser'),
            $errorMessage,
            $syntax
        );
    }

    protected function getCantParseArgumentErrorMessage(): string
    {
        return $this->__('Can\'t parse argument', 'graphql-parser');
    }

    public function createDocument(
        /** @var OperationInterface[] */
        array $operations,
        /** @var Fragment[] */
        array $fragments,
    ) {
        return new Document(
            $operations,
            $fragments,
        );
    }

    protected function createVariable(
        string $name,
        string $type,
        bool $isRequired,
        bool $isArray,
        bool $isArrayElementRequired,
        Location $location,
    ): Variable {
        return new ExtendedVariable(
            $name,
            $type,
            $isRequired,
            $isArray,
            $isArrayElementRequired,
            $location,
        );
    }

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    protected function createLeafField(
        string $name,
        ?string $alias,
        array $arguments,
        array $directives,
        Location $location,
    ): LeafField {
        return new ExtendedLeafField($name, $alias, $arguments, $directives, $location);
    }

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Directive[] $directives
     */
    protected function createRelationalField(
        string $name,
        ?string $alias,
        array $arguments,
        array $fieldsOrFragmentBonds,
        array $directives,
        Location $location
    ): RelationalField {
        return new ExtendedRelationalField(
            $name,
            $alias,
            $arguments,
            $fieldsOrFragmentBonds,
            $directives,
            $location
        );
    }

    /**
     * @param Argument[] $arguments
     */
    protected function createDirective(
        $name,
        array $arguments,
        Location $location,
    ): Directive {
        return new ExtendedDirective($name, $arguments, $location);
    }
}
