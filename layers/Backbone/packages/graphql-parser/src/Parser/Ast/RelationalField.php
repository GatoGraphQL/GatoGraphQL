<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Location;

class RelationalField extends AbstractAst implements FieldInterface
{
    use AstArgumentsTrait;
    use AstDirectivesTrait;

    /** @var FieldInterface[]|FragmentInterface[] */
    protected array $fieldOrFragmentReferences = [];

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentInterface[] $fieldOrFragmentReferences
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $name,
        protected ?string $alias,
        array $arguments,
        array $fieldOrFragmentReferences,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setFieldOrFragmentReferences($fieldOrFragmentReferences);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return FieldInterface[]|FragmentInterface[]
     */
    public function getFieldOrFragmentReferences(): array
    {
        return $this->fieldOrFragmentReferences;
    }

    public function hasFieldOrFragmentReferences(): bool
    {
        return count($this->fieldOrFragmentReferences) > 0;
    }

    /**
     * @param FieldInterface[]|FragmentInterface[] $fieldOrFragmentReferences
     */
    public function setFieldOrFragmentReferences(array $fieldOrFragmentReferences): void
    {
        /**
         * we cannot store fields by name because of TypedFragments
         */
        $this->fieldOrFragmentReferences = $fieldOrFragmentReferences;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function hasField(string $name, bool $deep = false): bool
    {
        foreach ($this->getFieldOrFragmentReferences() as $fieldOrFragmentReference) {
            if ($fieldOrFragmentReference instanceof FragmentInterface) {
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentReference;
            if ($field->getName() === $name
                || ($deep && $field instanceof RelationalField && $field->hasField($name))
            ) {
                return true;
            }
        }

        return false;
    }
}
