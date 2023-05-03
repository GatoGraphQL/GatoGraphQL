<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigCustomizableConfigurationBlock;

abstract class AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter extends AbstractBlockSchemaConfigurationExecuter
{
    /**
     * Only execute the Schema Configuration if block option
     * "Customize configuration? (Or use default from Settings?)"
     * has value `true` (i.e. "Use custom configuration")
     *
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    final protected function executeBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $customizeConfiguration = $schemaConfigBlockDataItem['attrs'][AbstractSchemaConfigCustomizableConfigurationBlock::ATTRIBUTE_NAME_CUSTOMIZE_CONFIGURATION] ?? false;
        if (!$customizeConfiguration) {
            $this->executeNotCustomizedSchemaConfiguration();
            return;
        }
        $this->doExecuteBlockSchemaConfiguration($schemaConfigBlockDataItem);
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    abstract protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void;

    protected function executeNotCustomizedSchemaConfiguration(): void
    {
        // By default, do nothing
    }

    protected function executeNoBlockSchemaConfiguration(): void
    {
        $this->executeNotCustomizedSchemaConfiguration();
    }
}
