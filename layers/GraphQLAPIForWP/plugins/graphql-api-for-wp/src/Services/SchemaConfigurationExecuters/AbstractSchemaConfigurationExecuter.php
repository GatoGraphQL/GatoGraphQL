<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractSchemaConfigurationExecuter implements SchemaConfigurationExecuterInterface
{
    use BasicServiceTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?BlockHelpers $blockHelpers = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setBlockHelpers(BlockHelpers $blockHelpers): void
    {
        $this->blockHelpers = $blockHelpers;
    }
    final protected function getBlockHelpers(): BlockHelpers
    {
        return $this->blockHelpers ??= $this->instanceManager->getInstance(BlockHelpers::class);
    }

    /**
     * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
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

    public function getEnablingModule(): ?string
    {
        return null;
    }

    /**
     * Only enable the service, if the corresponding module is also enabled
     */
    public function isServiceEnabled(): bool
    {
        $enablingModule = $this->getEnablingModule();
        if ($enablingModule !== null) {
            return $this->getModuleRegistry()->isModuleEnabled($enablingModule);
        }
        return true;
    }
}
