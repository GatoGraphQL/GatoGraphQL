<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Executers;

use GatoGraphQL\GatoGraphQL\Server\InternalGraphQLServerFactory;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;

class GraphQLServerNotReadyInternalGraphQLServerTestExecuter
{
    use OutputOriginalExceptionMessageTestExecuterTrait;

    public function __construct()
    {
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        $actions = $_GET['actions'] ?? null;
        if ($actions === null || !is_array($actions)) {
            return;
        }
        /** @var string[] $actions */
        if (!in_array(Actions::TEST_INTERNAL_GRAPHQL_SERVER_NOT_READY, $actions)) {
            return;
        }

        /**
         * Customize the WordPress error message, to indeed validate
         * that the thrown exception is the expected one.
         *
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

        // Just executing this code will throw the exception
        InternalGraphQLServerFactory::getInstance();
    }
}
