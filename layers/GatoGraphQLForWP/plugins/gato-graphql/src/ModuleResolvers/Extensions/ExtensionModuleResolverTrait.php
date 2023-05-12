<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\ContentProcessors\ExtensionPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\CommonModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ExtensionModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use ExtensionPluginMarkdownContentRetrieverTrait;
    use CommonModuleResolverTrait;
}
