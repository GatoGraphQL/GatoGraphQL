<?php

/**
 * Date: 01.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace GraphQLByPoP\GraphQLParser\Parser\Ast\Interfaces;

interface ValueInterface
{

    public function getValue();

    public function setValue($value);
}
