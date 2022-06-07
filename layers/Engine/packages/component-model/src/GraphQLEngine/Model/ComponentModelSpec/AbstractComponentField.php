<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\AbstractAst;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

abstract class AbstractComponentField extends AbstractAst implements ComponentFieldInterface
{
    protected FieldInterface $field;

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        ?string $alias = null,
        array $arguments = [],
        array $directives = [],
        ?Location $location = null,
    ) {
        $this->field = new LeafField(
            $name,
            $alias,
            $arguments,
            $directives,
            $location ?? LocationHelper::getNonSpecificLocation(),
        );
    }

    /**
     * Allow doing `array_unique` based on the underlying Field
     */
    public function __toString(): string
    {
        return $this->asFieldOutputQueryString();
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }

    public function getName(): string
    {
        return $this->field->getName();
    }

    public function getAlias(): ?string
    {
        return $this->field->getAlias();
    }

    /**
     * @return Argument[]
     */
    public function getArguments(): array
    {
        return $this->field->getArguments();
    }

    public function getArgument(string $name): ?Argument
    {
        return $this->field->getArgument($name);
    }

    public function setParent(RelationalField|Fragment|InlineFragment|OperationInterface $parent): void
    {
        $this->field->setParent($parent);
    }

    public function getParent(): RelationalField|Fragment|InlineFragment|OperationInterface
    {
        return $this->field->getParent();
    }

    public function asFieldOutputQueryString(): string
    {
        return $this->field->asFieldOutputQueryString();
    }

    public function asQueryString(): string
    {
        return $this->field->asQueryString();
    }

    public function getLocation(): Location
    {
        return $this->field->getLocation();
    }

    /**
     * @return Directive[]
     */
    public function getDirectives(): array
    {
        return $this->field->getDirectives();
    }

    public function hasDirectives(): bool
    {
        return $this->field->hasDirectives();
    }
}
