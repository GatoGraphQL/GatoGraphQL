<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Services\Blocks;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\OptionsBlockTrait;

class SchemaConfigSendHTTPRequestFieldsBlock extends AbstractSchemaConfigPROPluginPseudoBlock
{
    use PROPluginBlockTrait;
    use OptionsBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-send-http-request-fields';
    }

    public function getBlockPriority(): int
    {
        return 8080;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::SEND_HTTP_REQUEST_FIELDS;
    }

    /**
     * Add the locale language to the localized data?
     */
    protected function addLocalLanguage(): bool
    {
        return true;
    }

    /**
     * Default language for the script/component's documentation
     */
    protected function getDefaultLanguage(): ?string
    {
        // English
        return 'en';
    }
}
