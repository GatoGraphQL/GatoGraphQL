<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigResponseHeadersBlock;
use PoP\ComponentModel\Engine\EngineHookNames;

class ResponseHeadersBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigResponseHeadersBlock $schemaConfigCategoriesBlock = null;

    final protected function getSchemaConfigResponseHeadersBlock(): SchemaConfigResponseHeadersBlock
    {
        if ($this->schemaConfigCategoriesBlock === null) {
            /** @var SchemaConfigResponseHeadersBlock */
            $schemaConfigCategoriesBlock = $this->instanceManager->getInstance(SchemaConfigResponseHeadersBlock::class);
            $this->schemaConfigCategoriesBlock = $schemaConfigCategoriesBlock;
        }
        return $this->schemaConfigCategoriesBlock;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::RESPONSE_HEADERS;
    }

    /**
     * @param array<string,mixed> $schemaConfigBlockDataItem
     */
    protected function doExecuteBlockSchemaConfiguration(array $schemaConfigBlockDataItem): void
    {
        $entries = $schemaConfigBlockDataItem['attrs'][BlockAttributeNames::ENTRIES] ?? [];
        if ($entries === []) {
            return;
        }

        $responseHeaders = PluginStaticHelpers::getResponseHeadersFromEntries($entries);
        if ($responseHeaders === []) {
            return;
        }

        /**
         * Define the settings value through a hook.
         * Execute last so it overrides the default settings
         */
        App::addFilter(
            EngineHookNames::HEADERS,
            /**
             * @param array<string,string> $headers
             * @return array<string,string>
             */
            fn (array $headers): array => array_merge(
                $headers,
                $responseHeaders
            ),
            PHP_INT_MAX
        );
    }

    protected function getBlock(): BlockInterface
    {
        return $this->getSchemaConfigResponseHeadersBlock();
    }
}
