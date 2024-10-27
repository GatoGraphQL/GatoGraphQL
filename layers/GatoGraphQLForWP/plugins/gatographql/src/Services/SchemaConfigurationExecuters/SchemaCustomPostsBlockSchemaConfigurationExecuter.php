<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSchemaCustomPostsBlock;
use PoPCMSSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaCustomPostsBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaCustomPostsBlock $schemaConfigSchemaCustomPostsBlock = null;

    final protected function getSchemaConfigSchemaCustomPostsBlock(): SchemaConfigSchemaCustomPostsBlock
    {
        if ($this->schemaConfigSchemaCustomPostsBlock === null) {
            /** @var SchemaConfigSchemaCustomPostsBlock */
            $schemaConfigSchemaCustomPostsBlock = $this->instanceManager->getInstance(SchemaConfigSchemaCustomPostsBlock::class);
            $this->schemaConfigSchemaCustomPostsBlock = $schemaConfigSchemaCustomPostsBlock;
        }
        return $this->schemaConfigSchemaCustomPostsBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CUSTOMPOSTS;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $includedCustomPostTypes = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaCustomPostsBlock::ATTRIBUTE_NAME_INCLUDED_CUSTOM_POST_TYPES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            CustomPostsModule::class,
            CustomPostsEnvironment::QUERYABLE_CUSTOMPOST_TYPES
        );
        App::addFilter(
            $hookName,
            fn () => $includedCustomPostTypes,
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaCustomPostsBlock();
    }
}
