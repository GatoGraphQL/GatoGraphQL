<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\CommonModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use PluginMarkdownContentRetrieverTrait;
    use CommonModuleResolverTrait;

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return true;
    }
}
