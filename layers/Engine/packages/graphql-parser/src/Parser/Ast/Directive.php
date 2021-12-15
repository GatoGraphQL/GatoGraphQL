<?php

/**
 * Date: 3/17/17
 *
 * @author Volodymyr Rashchepkin <rashepkin@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Location;

class Directive extends AbstractAst
{
    use AstArgumentsTrait;


    public function __construct(private $name, array $arguments, Location $location)
    {
        parent::__construct($location);
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
    public function setName($name): void
    {
        $this->name = $name;
    }
}
