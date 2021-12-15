<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser\Ast;

use GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces\FragmentInterface;
use GraphQLByPoP\GraphQLParser\Parser\Location;

class FragmentReference extends AbstractAst implements FragmentInterface
{

    /** @var  string */
    protected $name;

    /**
     * @param string   $name
     */
    public function __construct($name, Location $location)
    {
        parent::__construct($location);

        $this->name = $name;
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
}
