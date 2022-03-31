<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

/**
 * A fragment bond is either a fragment reference, or an inline fragment
 */
interface FragmentBondInterface extends AstInterface
{
    public function setParent(RelationalField|Fragment|InlineFragment|OperationInterface $parent): void;

    public function getParent(): RelationalField|Fragment|InlineFragment|OperationInterface;
}
