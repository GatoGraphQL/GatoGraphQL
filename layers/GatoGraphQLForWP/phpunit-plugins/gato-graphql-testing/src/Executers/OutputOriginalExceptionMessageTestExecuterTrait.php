<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

trait OutputOriginalExceptionMessageTestExecuterTrait
{
    /**
     * Return the original exception message, stripping the stack trace
     *
     * @param array<string,mixed> $error
     */
    protected function customizeWordPressErrorMessage(string $message, array $error): string
    {
        $exceptionMessage = $error['message'];
        $pos = strpos($exceptionMessage, sprintf(' in %s', $error['file']));
        if ($pos === false) {
            return $exceptionMessage;
        }
        return substr($exceptionMessage, 0, $pos);
    }
}
