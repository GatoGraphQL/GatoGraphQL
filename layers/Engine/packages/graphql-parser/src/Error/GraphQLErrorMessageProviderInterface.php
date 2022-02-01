<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Error;

interface GraphQLErrorMessageProviderInterface
{
    public function getContextNotSetErrorMessage(string $variableName): string;
}
