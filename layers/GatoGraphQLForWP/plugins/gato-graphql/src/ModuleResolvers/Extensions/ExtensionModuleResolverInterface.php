<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\ModuleResolverInterface;

interface ExtensionModuleResolverInterface extends ModuleResolverInterface
{
    public function getGatoGraphQLExtensionPluginFile(string $module): string;
    public function getGatoGraphQLExtensionSlug(string $module): string;
    public function getWebsiteURL(string $module): string;
    public function getLogoURL(string $module): string;
}
