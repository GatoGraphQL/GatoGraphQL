<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\FieldInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class LeafField extends AbstractAst implements FieldInterface
{
    use WithArgumentsTrait;
    use WithDirectivesTrait;

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        private string $name,
        private ?string $alias,
        array $arguments,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setArguments($arguments);
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

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }
}
