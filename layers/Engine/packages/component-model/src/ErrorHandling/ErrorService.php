<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ErrorHandling;

use PoP\ComponentModel\Feedback\Tokens;

class ErrorService implements ErrorServiceInterface
{
    /**
     * @param string[]|null $path
     * @param string[]|null $argPath
     * @return array<string, mixed>
     */
    public function getErrorOutput(Error $error, ?array $path = null, ?array $argPath = null): array
    {
        $errorOutput = [
            Tokens::MESSAGE => $error->getMessageOrCode(),
        ];
        if ($path !== null) {
            $errorOutput[Tokens::PATH] = $path;
        }
        if ($data = $error->getData()) {
            $errorOutput[Tokens::EXTENSIONS] = $data;
        }
        if ($argPath !== null) {
            $errorOutput[Tokens::EXTENSIONS][Tokens::ARGUMENT_PATH] = $argPath;
        }
        foreach ($error->getNestedErrors() as $nestedError) {
            $errorOutput[Tokens::EXTENSIONS][Tokens::NESTED][] = $this->getErrorOutput($nestedError);
        }
        return $errorOutput;
    }
}
