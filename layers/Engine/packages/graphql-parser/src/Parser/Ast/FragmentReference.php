<?php

/**
 * Date: 23.11.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\GraphQLParser\Parser\Ast\Interfaces\FragmentInterface;
use PoP\GraphQLParser\Parser\Location;

class FragmentReference extends AbstractAst implements FragmentInterface
{
    public function __construct(protected string $name, Location $location)
    {
        parent::__construct($location);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
