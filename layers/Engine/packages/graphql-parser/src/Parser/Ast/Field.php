<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Ast\Interfaces\FieldInterface;
use PoP\GraphQLParser\Parser\Location;

class Field extends AbstractAst implements FieldInterface
{
    use AstArgumentsTrait;
    use AstDirectivesTrait;

    public function __construct(private string $name, private ?string $alias, array $arguments, array $directives, Location $location)
    {
        parent::__construct($location);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param null|string $alias
     */
    public function setAlias($alias): void
    {
        $this->alias = $alias;
    }

    public function hasFields()
    {
        return false;
    }

    public function getFields()
    {
        return [];
    }
}
