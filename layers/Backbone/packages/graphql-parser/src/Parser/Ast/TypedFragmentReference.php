<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Location;

class TypedFragmentReference extends AbstractAst implements FragmentInterface, WithDirectivesInterface
{
    use AstDirectivesTrait;

    /**
     * @param FieldInterface[]|FragmentInterface[] $fieldOrFragmentReferences
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $typeName,
        protected array $fieldOrFragmentReferences,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
    }

    /**
     * @return FieldInterface[]|FragmentInterface[]
     */
    public function getFieldOrFragmentReferences(): array
    {
        return $this->fieldOrFragmentReferences;
    }

    /**
     * @param FieldInterface[]|FragmentInterface[] $fieldOrFragmentReferences
     */
    public function setFieldOrFragmentReferences(array $fieldOrFragmentReferences): void
    {
        $this->fieldOrFragmentReferences = $fieldOrFragmentReferences;
    }

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function setTypeName(string $typeName): void
    {
        $this->typeName = $typeName;
    }
}
