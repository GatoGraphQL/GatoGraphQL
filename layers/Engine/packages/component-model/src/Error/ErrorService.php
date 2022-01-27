<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Error;

use PoP\Root\Services\BasicServiceTrait;
use PoP\ComponentModel\Feedback\Tokens;

class ErrorService implements ErrorServiceInterface
{
    use BasicServiceTrait;

    /**
     * @param string[]|null $path
     * @return array<string, mixed>
     */
    public function getErrorOutput(Error $error, ?array $path = null, ?string $argName = null): array
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
        if ($argName !== null) {
            $errorOutput[Tokens::EXTENSIONS][Tokens::ARGUMENT_PATH] = array_merge(
                [$argName],
                $errorOutput[Tokens::EXTENSIONS][Tokens::ARGUMENT_PATH] ?? []
            );
        }
        foreach ($error->getNestedErrors() as $nestedError) {
            $errorOutput[Tokens::EXTENSIONS][Tokens::NESTED][] = $this->getErrorOutput($nestedError, null, $argName);
        }
        return $errorOutput;
    }
}
