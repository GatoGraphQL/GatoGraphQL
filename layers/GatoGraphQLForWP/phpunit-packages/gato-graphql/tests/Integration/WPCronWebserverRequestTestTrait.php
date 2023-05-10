<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
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
        $queryArgs = [
            'actions' => [
                Actions::TEST_WP_CRON
            ],
        ];

        return GeneralUtils::addQueryArgs(
            $queryArgs,
            $endpoint
        );
    }
}
