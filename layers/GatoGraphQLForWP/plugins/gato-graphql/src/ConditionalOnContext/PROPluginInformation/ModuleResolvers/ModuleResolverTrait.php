<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\CommonModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\ContentProcessors\PROPluginMarkdownContentRetrieverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use PROPluginMarkdownContentRetrieverTrait;
    use CommonModuleResolverTrait;

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return true;
    }
}
