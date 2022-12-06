<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class RuntimeLocation extends Location
{
    public function __construct(
        int $line,
        int $column,
        protected ?AstInterface $astNode,
    ) {
        parent::__construct(
            $line,
            $column
        );
    }

    public function getASTNode(): ?AstInterface
    {
        return $this->astNode;
    }
}
