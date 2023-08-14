<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\BlockHelpers;

abstract class AbstractBlockSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter
{
    private ?BlockHelpers $blockHelpers = null;

    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        if ($this->blockHelpers === null) {
            /** @var BlockHelpers */
            $blockHelpers = $this->instanceManager->getInstance(BlockHelpers::class);
            $this->blockHelpers = $blockHelpers;
        }
        return $this->blockHelpers;
    }

    /**
     * @return array<string,mixed>|null Data inside the block is saved as key (string) => value
     */
    protected function getSchemaConfigBlockDataItem(int $schemaConfigurationID): ?array
    {
        $block = $this->getBlock();
        return $this->getBlockHelpers()->getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            $block
        );
    }

    abstract protected function getBlock(): BlockInterface;

    /**
     * Only execute the Schema Configuration if block option
     * "Customize configuration? (Or use default from Settings?)"
     * has value `true` (i.e. "Use custom configuration")
     */
    final public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem === null) {
            $this->executeNoBlockSchemaConfiguration();
            return;
        }
        $this->executeBlockSchemaConfiguration($schemaConfigBlockDataItem);
    }

    protected function executeNoBlockSchemaConfiguration(): void
    {
        // By default, do nothing
    }

    /**
     * By default, do nothing
     */
    public function executeNoneAppliedSchemaConfiguration(): void
    {
        $this->executeNoBlockSchemaConfiguration();
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    abstract protected function executeBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void;
}
