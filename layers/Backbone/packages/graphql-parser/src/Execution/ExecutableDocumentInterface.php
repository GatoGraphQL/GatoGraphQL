<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;

interface ExecutableDocumentInterface
{
    /**
     * @throws InvalidRequestException
     */
    public function validateAndMerge(): void;
}
