<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Error;

use PoP\BasicService\BasicServiceTrait;
use PoP\ComponentModel\Feedback\Tokens;
use stdClass;

class ErrorService implements ErrorServiceInterface
{
    use BasicServiceTrait;
    
    /**
     * Encode the array, and trim to 500 chars max
     *
     * @param mixed[] $value
     */
    public function jsonEncodeArrayOrStdClassValue(array|stdClass $value): string
    {
        return mb_strimwidth(
            json_encode($value),
            0,
            500,
            $this->getTranslationAPI()->__('...', 'component-model')
        );
    }
    
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
