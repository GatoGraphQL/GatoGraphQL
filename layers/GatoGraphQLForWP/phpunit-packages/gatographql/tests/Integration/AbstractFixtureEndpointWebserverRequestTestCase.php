<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;

abstract class AbstractFixtureEndpointWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use FixtureEndpointWebserverRequestTestCaseTrait;

    /**
     * Since PHPUnit v10, this is not possible anymore!
     */
    // final public function dataSetAsString(): string
    // {
    //     return $this->addFixtureFolderInfo(parent::dataSetAsString());
    // }

    /**
     * Retrieve all GraphQL query files and their expected
     * responses from under the "/fixture" folder
     */
    final public static function provideEndpointEntries(): array
    {
        return static::provideFixtureEndpointEntries(
            static::getFixtureFolder(),
            static::getResponseFixtureFolder()
        );
    }
}
