<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

class SubscriptionOperation extends AbstractOperation
{
    public function getOperationType(): string
    {
        return OperationTypes::SUBSCRIPTION;
    }
}
