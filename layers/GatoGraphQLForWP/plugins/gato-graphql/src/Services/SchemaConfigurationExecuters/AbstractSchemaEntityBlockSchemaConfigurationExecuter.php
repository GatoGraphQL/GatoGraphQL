<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Services\Helpers\UserSettingsHelpers;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters\AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurators\SchemaEntityConfiguratorInterface;

abstract class AbstractSchemaEntityBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter
{
    private ?UserSettingsHelpers $userSettingsHelpers = null;

    final public function setUserSettingsHelpers(UserSettingsHelpers $userSettingsHelpers): void
    {
        $this->userSettingsHelpers = $userSettingsHelpers;
    }
    final protected function getUserSettingsHelpers(): UserSettingsHelpers
    {
        /** @var UserSettingsHelpers */
        return $this->userSettingsHelpers ??= $this->instanceManager->getInstance(UserSettingsHelpers::class);
    }

    protected function mustAlsoExecuteWhenSchemaConfigurationModuleIsDisabled(): bool
    {
        return true;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $customPostIDs = $schemaConfigBlockDataItem['attrs'][$this->getCustomPostListsAttributeName()] ?? [];

        /**
         * Cast to int[]
         */
        $customPostIDs = array_map(
            fn (int|string $item) => (int) $item,
            $customPostIDs
        );

        /** @var int[] $customPostIDs */
        $this->executeCustomPostListsBlockSchemaConfiguration($customPostIDs);
    }

    abstract protected function getCustomPostListsAttributeName(): string;

    /**
     * @param int[] $customPostIDs
     */
    protected function executeCustomPostListsBlockSchemaConfiguration(array $customPostIDs): void
    {
        if ($customPostIDs === []) {
            return;
        }
        $customPostSchemaEntityConfigurator = $this->getSchemaEntityConfigurator();
        foreach ($customPostIDs as $customPostID) {
            $customPostSchemaEntityConfigurator->executeSchemaEntityConfiguration($customPostID);
        }
    }

    abstract protected function getSchemaEntityConfigurator(): SchemaEntityConfiguratorInterface;

    protected function executeNotCustomizedSchemaConfiguration(): void
    {
        /** @var string */
        $enablingModule = $this->getEnablingModule();
        $customPostIDs = $this->getUserSettingsHelpers()->getUserDefaultSettingCustomPostValueIDs(
            $enablingModule,
        );
        $this->executeCustomPostListsBlockSchemaConfiguration($customPostIDs);
    }
}
