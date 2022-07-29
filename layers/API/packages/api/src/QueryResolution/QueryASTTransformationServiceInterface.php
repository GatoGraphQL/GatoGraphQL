<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

interface QueryASTTransformationServiceInterface
{
    public function transformASTForMultipleQueryExecution(): void;
}
