<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Hooks;

use PoP\Root\Hooks\AbstractHookSet;

use function wp_get_theme;

/**
 * If Bricks (or another theme) is active, and we use the Customizer to
 * do a Live Preview of another theme, then Bricks will not be loaded,
 * and accessing classes from Bricks (eg: via the BricksAPI) will throw
 * an error.
 *
 * So, we need to check if Bricks is actually loaded.
 */
abstract class AbstractThemeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        if (!$this->isThemeLoaded()) {
            return;
        }
        $this->initTheme();
    }

    protected function isThemeLoaded(): bool
    {
        // Check that the theme is currently loaded by WordPress
        $currentTheme = wp_get_theme();
        return $currentTheme->get_stylesheet() === $this->getThemeStylesheetSlug();
    }

    abstract protected function getThemeStylesheetSlug(): string;

    abstract protected function initTheme(): void;
}
