<?php

declare(strict_types=1);

namespace PoPWPSchema\Settings\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;

class SettingsTypeAPI implements SettingsTypeAPIInterface
{
    use BasicServiceTrait;

    /**
     * The Gutenberg editor is enabled when the Classic editor
     * plugin is not enabled
     *
     * @see https://wordpress.org/plugins/classic-editor/
     */
    public function isGutenbergEditorEnabled(): bool
    {
        return !PluginStaticHelpers::isWordPressPluginActive('classic-editor/classic-editor.php');
    }
}
