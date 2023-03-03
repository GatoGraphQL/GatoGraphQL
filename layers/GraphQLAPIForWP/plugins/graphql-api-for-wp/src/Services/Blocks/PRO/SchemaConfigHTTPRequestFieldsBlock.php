<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks\PRO;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\PRO\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\OptionsBlockTrait;

class SchemaConfigHTTPRequestFieldsBlock extends AbstractSchemaConfigPROPseudoBlock
{
    use PROPluginBlockTrait;
    use OptionsBlockTrait;

    protected function getBlockName(): string
    {
        return 'schema-config-http-request-fields';
    }

    public function getBlockPriority(): int
    {
        return 2100;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaTypeModuleResolver::HTTP_REQUEST_FIELDS;
    }

    // protected function getBlockTitle(): string
    // {
    //     return \__('HTTP Request Fields', 'graphql-api-pro');
    // }

    // protected function getRenderBlockLabel(): string
    // {
    //     return $this->__('Allowed URLs', 'graphql-api-pro');
    // }

    // /**
    //  * Register style-index.css
    //  */
    // protected function registerCommonStyleCSS(): bool
    // {
    //     return true;
    // }

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
