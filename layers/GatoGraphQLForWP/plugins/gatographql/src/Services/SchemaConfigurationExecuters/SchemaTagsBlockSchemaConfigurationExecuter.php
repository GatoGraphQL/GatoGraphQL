<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigSchemaTagsBlock;
use PoPCMSSchema\Tags\Environment as TagsEnvironment;
use PoPCMSSchema\Tags\Module as TagsModule;
use PoP\Root\Module\ModuleConfigurationHelpers;

class SchemaTagsBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigSchemaTagsBlock $schemaConfigTagsBlock = null;

    final protected function getSchemaConfigSchemaTagsBlock(): SchemaConfigSchemaTagsBlock
    {
        if ($this->schemaConfigTagsBlock === null) {
            /** @var SchemaConfigSchemaTagsBlock */
            $schemaConfigTagsBlock = $this->instanceManager->getInstance(SchemaConfigSchemaTagsBlock::class);
            $this->schemaConfigTagsBlock = $schemaConfigTagsBlock;
        }
        return $this->schemaConfigTagsBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SCHEMA_CATEGORIES;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $includedTagTaxonomies = $schemaConfigBlockDataItem['attrs'][SchemaConfigSchemaTagsBlock::ATTRIBUTE_NAME_INCLUDED_TAG_TAXONOMIES] ?? [];
        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        $hookName = ModuleConfigurationHelpers::getHookName(
            TagsModule::class,
            TagsEnvironment::QUERYABLE_TAG_TAXONOMIES
        );
        App::addFilter(
            $hookName,
            fn () => $includedTagTaxonomies,
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigSchemaTagsBlock();
    }
}
