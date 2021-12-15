<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Ast\Interfaces\FieldInterface;
use PoP\GraphQLParser\Parser\Ast\Interfaces\WithDirectivesInterface;
use PoP\GraphQLParser\Parser\Location;

class Field extends AbstractAst implements FieldInterface, WithDirectivesInterface
{
    use AstArgumentsTrait;
    use AstDirectivesTrait;

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(private string $name, private ?string $alias, array $arguments, array $directives, Location $location)
    {
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
