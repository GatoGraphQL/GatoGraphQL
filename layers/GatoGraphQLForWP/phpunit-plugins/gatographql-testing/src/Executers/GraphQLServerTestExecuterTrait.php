<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

trait GraphQLServerTestExecuterTrait
{
    /**
     * Customize the WordPress error message, to indeed validate
     * that the thrown exception is the expected one.
     *
     * @see wp-includes/class-wp-fatal-error-handler.php
     */
    protected function setupToOutputOriginalExceptionMessage(): void
    {
        /**
         * @see wp-includes/class-wp-fatal-error-handler.php
         */
        \add_filter(
            'wp_php_error_message',
            /**
             * @param array<string,mixed> $error
             */
            fn (string $message, array $error): string => $this->customizeWordPressErrorMessage($message, $error),
            10,
            2
        );
    }

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

    /**
     * Print the message under an artificial entry
     * in the JSON response, and exit
     */
    protected function outputArtificialErrorAsJSONResponse(string $errorMessage): never
    {
        $this->outputJSONResponseAndExit(['artificialError' => $errorMessage]);
    }

    /**
     * @param array<string,mixed> $json
     */
    protected function outputJSONResponseAndExit(array $json): never
    {
        header(sprintf(
            '%s: %s',
            'Content-Type',
            'application/json'
        ));
        /** @var string */
        $jsonResponse = json_encode($json);
        echo $jsonResponse;
        exit;
    }
}
