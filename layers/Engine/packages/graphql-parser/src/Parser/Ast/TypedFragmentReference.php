<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Ast\Interfaces\FragmentInterface;
use PoP\GraphQLParser\Parser\Ast\Interfaces\WithDirectivesInterface;
use PoP\GraphQLParser\Parser\Location;

class TypedFragmentReference extends AbstractAst implements FragmentInterface, WithDirectivesInterface
{
    use AstDirectivesTrait;

    /**
     * @param Field[]|Query[] $fields
     * @param Directive[] $directives
     */
    public function __construct(protected string $typeName, protected array $fields, array $directives, Location $location)
    {
        parent::__construct($location);
        $this->setDirectives($directives);
    }

    /**
     * @return Field[]|Query[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param Field[]|Query[] $fields
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
