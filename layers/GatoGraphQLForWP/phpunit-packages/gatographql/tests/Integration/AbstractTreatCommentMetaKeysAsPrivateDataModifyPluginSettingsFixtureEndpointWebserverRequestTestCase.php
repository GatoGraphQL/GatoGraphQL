<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

abstract class AbstractTreatCommentMetaKeysAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractTreatMetaKeysAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-comment-meta';
    }
}
