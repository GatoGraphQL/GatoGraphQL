<?php

/*
 * This file is a part of GraphQL project.
 *
 * @author Alexandr Viniychuk <a@viniychuk.com>
 * created: 12:26 PM 5/14/16
 */

namespace PoP\GraphQLParser\Parser\Ast\Interfaces;

use PoP\GraphQLParser\Parser\Ast\Directive;

interface WithDirectivesInterface
{
    /**
     * @param Directive[] $directives
     */
    public function setDirectives(array $directives): void;
    
    /**
     * @return Directive[]
     */
    public function getDirectives(): array;

    public function hasDirectives(): bool;
}
