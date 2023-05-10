<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;

trait WPCronWebserverRequestTestTrait
{
    /**
     * @param array<string,mixed> $options
     */
    protected function getWPCronEndpoint(
        string $endpoint,
        array $options = []
    ): string {
        $timestamp = $options['timestamp'];
        $queryArgs = [
            'actions' => [
                Actions::TEST_WP_CRON
            ],
            Params::TIMESTAMP => $timestamp,
        ];

        return GeneralUtils::addQueryArgs(
            $queryArgs,
            $endpoint
        );
    }
}
