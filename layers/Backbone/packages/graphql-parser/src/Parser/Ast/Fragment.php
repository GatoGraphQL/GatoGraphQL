<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\WithDirectivesInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class Fragment extends AbstractAst implements WithDirectivesInterface
{
    use AstDirectivesTrait;

    /**
     * @param Directive[] $directives
     * @param FieldInterface[]|FragmentBondInterface[] $fieldOrFragmentReferences
     */
    public function __construct(
        protected string $name,
        protected string $model,
        array $directives,
        protected array $fieldOrFragmentReferences,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    /**
     * @return FieldInterface[]|FragmentBondInterface[]
     */
    public function getFieldOrFragmentReferences(): array
    {
        return $this->fieldOrFragmentReferences;
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldOrFragmentReferences
     */
    public function setFieldOrFragmentReferences(array $fieldOrFragmentReferences): void
    {
        $this->fieldOrFragmentReferences = $fieldOrFragmentReferences;
    }
}
