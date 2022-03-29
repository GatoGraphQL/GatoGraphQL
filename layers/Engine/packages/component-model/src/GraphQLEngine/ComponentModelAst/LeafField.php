<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\ComponentModelAst;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField as UpstreamLeafField;
use PoP\Root\Exception\ShouldNotHappenException;

class LeafField extends UpstreamLeafField implements FieldInterface
{
    use NonLocatableAstTrait;

    /**
     * If $queryField is provided, obtain all the properties from it,
     * ignoring the other provided arguments. Otherwise, use the arguments.
     *
     * Either one of $queryField or $name must be provided.
     *
     * @param Argument[] $arguments
     * @param Directive[] $directives
     *
     * @throws ShouldNotHappenException If both $queryField and $name are null
     */
    public function __construct(
        protected ?UpstreamLeafField $queryField = null,
        ?string $name = null,
        ?string $alias = null,
        array $arguments = [],
        array $directives = [],
        protected bool $skipOutputIfNull = false,
    ) {
        if ($queryField === null && $name === null) {
            throw new ShouldNotHappenException(
                $this->__('Either $queryField or $name must be provided', 'component-model')
            );
        }
        if ($queryField !== null) {
            parent::__construct(
                $queryField->getName(),
                $queryField->getAlias(),
                $queryField->getArguments(),
                $queryField->getDirectives(),
                $queryField->getLocation(),
            );
            return;
        }
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            $this->createPseudoLocation(),
        );
    }

    public function isSkipOutputIfNull(): bool
    {
        return $this->skipOutputIfNull;
    }
}
