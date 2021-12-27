<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Location;

class RelationalField extends AbstractAst implements FieldInterface
{
    use AstArgumentsTrait;
    use AstDirectivesTrait;

    /** @var FieldInterface[]|FragmentBondInterface[] */
    protected array $fieldsOrFragmentBonds = [];

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $name,
        protected ?string $alias,
        array $arguments,
        array $fieldsOrFragmentBonds,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return FieldInterface[]|FragmentBondInterface[]
     */
    public function getFieldsOrFragmentBonds(): array
    {
        return $this->fieldsOrFragmentBonds;
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     */
    public function setFieldsOrFragmentBonds(array $fieldsOrFragmentBonds): void
    {
        /**
         * we cannot store fields by name because of TypedFragments
         */
        $this->fieldsOrFragmentBonds = $fieldsOrFragmentBonds;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }
}
