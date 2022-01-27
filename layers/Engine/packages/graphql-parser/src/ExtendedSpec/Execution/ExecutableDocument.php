<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\Root\Services\StandaloneServiceTrait;
use PoP\GraphQLParser\Facades\Query\QueryAugmenterServiceFacade;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;

class ExecutableDocument extends UpstreamExecutableDocument
{
    use StandaloneServiceTrait;

    /**
     * Override to support the "multiple query execution" feature:
     * If passing operation name `__ALL`, then execute all operations (hack)
     *
     * @return OperationInterface[]
     */
    protected function extractRequestedOperations(): array
    {
        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        if ($queryAugmenterService->isExecutingAllOperations($this->context->getOperationName())) {
            return $this->document->getOperations();
        }

        return parent::extractRequestedOperations();
    }
}
