<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractSchemaConfigurationExecuter implements SchemaConfigurationExecuterInterface
{
    protected InstanceManagerInterface $instanceManager;
    protected ModuleRegistryInterface $moduleRegistry;
    protected BlockHelpers $blockHelpers;

    #[Required]
    public function autowireAbstractSchemaConfigurationExecuter(InstanceManagerInterface $instanceManager, ModuleRegistryInterface $moduleRegistry, BlockHelpers $blockHelpers): void
    {
        $this->instanceManager = $instanceManager;
        $this->moduleRegistry = $moduleRegistry;
        $this->blockHelpers = $blockHelpers;
    }

    /**
     * @return array<string, mixed>|null Data inside the block is saved as key (string) => value
     */
    protected function getSchemaConfigBlockDataItem(int $schemaConfigurationID): ?array
    {
        $block = $this->getBlock();
        return $this->blockHelpers->getSingleBlockOfTypeFromCustomPost(
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
            return $this->moduleRegistry->isModuleEnabled($enablingModule);
        }
        return true;
    }
}
