<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser;

use PoP\BasicService\BasicServiceTrait;
use PoP\GraphQLParser\Parser\Ast\ArgumentValue\Variable as ExtendedVariable;
use PoP\GraphQLParser\Parser\Ast\Directive as ExtendedDirective;
use PoP\GraphQLParser\Parser\Ast\Field as ExtendedField;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Ast\Field;
use PoPBackbone\GraphQLParser\Parser\Location;
use PoPBackbone\GraphQLParser\Parser\Parser as UpstreamParser;

class Parser extends UpstreamParser
{
    use BasicServiceTrait;

    protected function getIncorrectRequestSyntaxErrorMessage(): string
    {
        return $this->getTranslationAPI()->__('Incorrect request syntax', 'graphql-parser');
    }

    protected function getCantParseArgumentErrorMessage(): string
    {
        return $this->getTranslationAPI()->__('Can\'t parse argument', 'graphql-parser');
    }

    protected function createVariable(
        string $name,
        string $type,
        bool $required,
        bool $isArray,
        bool $arrayElementNullable,
        Location $location,
    ): Variable {
        return new ExtendedVariable(
            $name,
            $type,
            $required,
            $isArray,
            $arrayElementNullable,
            $location,
        );
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
        return new ExtendedField($name, $alias, $arguments, $directives, $location);
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
