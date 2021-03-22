<?php

/**
 * Date: 3/17/17
 *
 * @author Volodymyr Rashchepkin <rashepkin@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser\Ast;

use GraphQLByPoP\GraphQLParser\Parser\Location;

class Directive extends AbstractAst
{
    use AstArgumentsTrait;

    /** @var string */
    private $name;


    /**
     * @param string   $name
     */
    public function __construct($name, array $arguments, Location $location)
    {
        parent::__construct($location);

        $this->name = $name;
        $this->setArguments($arguments);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
