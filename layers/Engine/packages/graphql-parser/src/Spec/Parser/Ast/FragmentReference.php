<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class FragmentReference extends AbstractAst implements FragmentBondInterface
{
    public function __construct(
        protected readonly string $name,
        Location $location,
    ) {
        parent::__construct($location);
    }

    protected function doAsQueryString(): string
    {
        return sprintf(
            '...%s',
            $this->name
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}
