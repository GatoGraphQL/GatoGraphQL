<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\BlockInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigCustomPostsBlock;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoP\Root\Module\ModuleConfigurationHelpers;

class CustomPostsSchemaConfigurationExecuter extends AbstractSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigCustomPostsBlock $schemaConfigCustomPostsBlock = null;

    final public function setSchemaConfigCustomPostsBlock(SchemaConfigCustomPostsBlock $schemaConfigCustomPostsBlock): void
    {
        $this->schemaConfigCustomPostsBlock = $schemaConfigCustomPostsBlock;
    }
    final protected function getSchemaConfigCustomPostsBlock(): SchemaConfigCustomPostsBlock
    {
        /** @var SchemaConfigCustomPostsBlock */
        return $this->schemaConfigCustomPostsBlock ??= $this->instanceManager->getInstance(SchemaConfigCustomPostsBlock::class);
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS;
    }

    public function executeSchemaConfiguration(int $schemaConfigurationID): void
    {
        $schemaConfigBlockDataItem = $this->getSchemaConfigBlockDataItem($schemaConfigurationID);
        if ($schemaConfigBlockDataItem !== null) {
            $includedCustomPostTypes = $schemaConfigBlockDataItem['attrs'][SchemaConfigCustomPostsBlock::ATTRIBUTE_NAME_INCLUDED_CUSTOM_POST_TYPES] ?? [];
            /**
             * Define the settings value through a hook.
             * Execute last so it overrides the default settings
             */
            $hookName = ModuleConfigurationHelpers::getHookName(
                CustomPostsModule::class,
                CustomPostsEnvironment::QUERYABLE_CUSTOMPOST_TYPES
            );
            \add_filter(
                $hookName,
                fn () => $includedCustomPostTypes,
                PHP_INT_MAX
            );
        }
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigCustomPostsBlock();
    }
}
