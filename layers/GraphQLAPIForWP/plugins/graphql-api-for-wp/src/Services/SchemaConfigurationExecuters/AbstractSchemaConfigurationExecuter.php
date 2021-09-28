<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

abstract class AbstractSchemaConfigurationExecuter implements SchemaConfigurationExecuterInterface
{
    protected InstanceManagerInterface $instanceManager;
    protected ModuleRegistryInterface $moduleRegistry;
    protected BlockHelpers $blockHelpers;

    #[Required]
    public function autowireAbstractSchemaConfigurationExecuter(InstanceManagerInterface $instanceManager, ModuleRegistryInterface $moduleRegistry, BlockHelpers $blockHelpers)
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
        /**
         * @var AbstractBlock
         */
        $block = $this->instanceManager->getInstance($this->getBlockClass());
        return $this->blockHelpers->getSingleBlockOfTypeFromCustomPost(
            $schemaConfigurationID,
            $block
        );
    }

    abstract protected function getBlockClass(): string;

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
