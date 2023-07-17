<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\Blocks\BlockInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigResponseHeadersBlock;
use PoPCMSSchema\Categories\Environment as CategoriesEnvironment;
use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoP\ComponentModel\Engine\EngineHookNames;
use PoP\Root\Module\ModuleConfigurationHelpers;

class ResponseHeadersBlockSchemaConfigurationExecuter extends AbstractCustomizableConfigurationBlockSchemaConfigurationExecuter implements PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface, EndpointSchemaConfigurationExecuterServiceTagInterface
{
    private ?SchemaConfigResponseHeadersBlock $schemaConfigCategoriesBlock = null;

    final public function setSchemaConfigResponseHeadersBlock(SchemaConfigResponseHeadersBlock $schemaConfigCategoriesBlock): void
    {
        $this->schemaConfigCategoriesBlock = $schemaConfigCategoriesBlock;
    }
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
        $entries = $attributes[BlockAttributeNames::ENTRIES] ?? [];
        if ($entries === []) {
            return;
        }

        /**
         * Convert the entries from string[] into an array of string => string:
         *   header name => header value
         *
         * @var array<string,string> Header name => value
         */
        $responseHeaders = [];
        foreach ($entries as $entry) {
            $entry = trim($entry);
            $separatorPos = strpos($entry, ':');
            if ($separatorPos === false) {
                $headerName = $entry;
                $headerValue = '';
            } else {
                $headerName = trim(substr($entry, 0, $separatorPos));
                $headerValue = trim(substr($entry, $separatorPos + strlen(':')));
            }
            if ($headerName === '') {
                continue;
            }
            $responseHeaders[$headerName] = $headerValue;
        }
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
