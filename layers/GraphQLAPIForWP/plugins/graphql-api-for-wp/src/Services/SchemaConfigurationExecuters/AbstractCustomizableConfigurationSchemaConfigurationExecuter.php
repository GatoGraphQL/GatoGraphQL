<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractSchemaConfigCustomizableConfigurationBlock;

abstract class AbstractCustomizableConfigurationSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter
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
            return;
        }
        $customizeConfiguration = $schemaConfigBlockDataItem['attrs'][AbstractSchemaConfigCustomizableConfigurationBlock::ATTRIBUTE_NAME_CUSTOMIZE_CONFIGURATION] ?? false;
        if (!$customizeConfiguration) {
            return;
        }
        $this->doExecuteSchemaConfiguration($schemaConfigurationID);
    }

    abstract protected function doExecuteSchemaConfiguration(int $schemaConfigurationID): void;
}
