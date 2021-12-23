<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;

interface ExecutableDocumentInterface
{
    /**
     * Integrate the variableValues into the included Operations in the Document.
     * If some variable is missing, or other, throw an exception to signify
     * that the validation fail.
     *
     * @throws InvalidRequestException
     */
    public function validateAndMerge(): void;
}
