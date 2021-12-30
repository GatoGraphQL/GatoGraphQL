<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser;

use PoP\BasicService\BasicServiceTrait;
use PoP\GraphQLParser\Parser\Ast\ArgumentValue\Variable as ExtendedVariable;
use PoP\GraphQLParser\Parser\Ast\Directive as ExtendedDirective;
use PoP\GraphQLParser\Parser\Ast\Document;
use PoP\GraphQLParser\Parser\Ast\LeafField as ExtendedLeafField;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Ast\LeafField;
use PoPBackbone\GraphQLParser\Parser\Location;
use PoPBackbone\GraphQLParser\Parser\Parser as UpstreamParser;

class Parser extends UpstreamParser implements ParserInterface
{
    use BasicServiceTrait;

    protected function getIncorrectRequestSyntaxErrorMessage(?string $syntax): string
    {
        $errorMessage = $this->getTranslationAPI()->__('Incorrect request syntax', 'graphql-parser');
        if ($syntax === null) {
            return $errorMessage;
        }
        return \sprintf(
            $this->getTranslationAPI()->__('%s: \'%s\'', 'graphql-parser'),
            $errorMessage,
            $syntax
        );
    }

    protected function getCantParseArgumentErrorMessage(): string
    {
        return $this->getTranslationAPI()->__('Can\'t parse argument', 'graphql-parser');
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
     */
    protected function createDirective(
        $name,
        array $arguments,
        Location $location,
    ): Directive {
        return new ExtendedDirective($name, $arguments, $location);
    }
}
