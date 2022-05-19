<?php

class PoP_EditorUtils
{
    public static function init(): void
    {
        if (defined('POP_ENGINEWEBPLATFORM_INITIALIZED')) {
            // If no editor has been initialized, just create the main one, always, at the footer
            // This will be called for HTML output, not for JSON output
            \PoP\Root\App::addAction('wp_footer', array(PoP_EditorUtils::class, 'createMainEditor'));
        }
    }

    public static function createMainEditor()
    {
        ob_start();
        wp_editor('', GD_COMPONENTSETTINGS_EDITOR_NAME);
        ob_get_clean();
    }

    public static function getEditorCode($editor_id, $value = '', $options = array())
    {
        ob_start();
        wp_editor($value, $editor_id, $options);
        return ob_get_clean();
    }
}

/**
 * Initialization
 */
PoP_EditorUtils::init();
