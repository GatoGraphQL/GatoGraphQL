<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Scripts;

use GatoGraphQL\GatoGraphQL\PluginApp;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait MainPluginScriptTrait
{
    protected function getPluginDir(): string
    {
        return PluginApp::getMainPlugin()->getPluginDir();
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim(PluginApp::getMainPlugin()->getPluginURL(), '/');
    }
}
