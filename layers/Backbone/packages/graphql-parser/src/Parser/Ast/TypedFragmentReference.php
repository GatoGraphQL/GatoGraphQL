<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\FragmentInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\WithDirectivesInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class TypedFragmentReference extends AbstractAst implements FragmentInterface, WithDirectivesInterface
{
    use AstDirectivesTrait;

    /**
     * @param FieldInterface[] $fields
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $typeName,
        protected array $fields,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
    }

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param FieldInterface[] $fields
     */
    public function setFields(array $fields): void
    {
        $this->fields = $fields;
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
