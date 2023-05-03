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
     */
    final public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem === null) {
            $this->executeNotCustomizedSchemaConfiguration();
            return;
        }
        $customizeConfiguration = $schemaConfigBlockDataItem['attrs'][AbstractSchemaConfigCustomizableConfigurationBlock::ATTRIBUTE_NAME_CUSTOMIZE_CONFIGURATION] ?? false;
        if (!$customizeConfiguration) {
            $this->executeNotCustomizedSchemaConfiguration();
            return;
        }
        $this->doExecuteSchemaConfiguration($schemaConfigBlockDataItem);
    }

    /**
     * @param array<string,mixed> $blockDataItem
     */
    abstract protected function doExecuteSchemaConfiguration(array $blockDataItem): void;

    protected function executeNotCustomizedSchemaConfiguration(): void
    {
        // By default, do nothing
    }
}
