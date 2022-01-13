<?php

class PoP_ServerSideRendering_Utils
{
    protected static $scripts;

    public static function init(): void
    {
        self::$scripts = array();

        // Priority 1: after printing 'wp_print_head_scripts' in the footer (priority 1)
        \PoP\Root\App::getHookManager()->addAction(
            'popcms:footer',
            array(PoP_ServerSideRendering_Utils::class, 'printScripts'),
            2
        );
    }

    public static function printScripts()
    {

        // Add the script tags once again if we defined to have them after the html
        // If doing disableJs, then do nothing
        if (PoP_SSR_ServerUtils::includeScriptsAfterHtml()) {
            if (self::$scripts) {
                // Print all the scripts under one unique <script> tag
                printf(
                    '<script type="text/javascript">%s</script>',
                    implode(PHP_EOL, self::$scripts)
                );
            }
        }
    }

    public static function renderPagesection($pagesection_settings_id, $target = null)
    {
        $html = PoP_ServerSideRenderingFactory::getInstance()->renderTarget($pagesection_settings_id, null, $target);

        // Extract the script tags if either we need to add them after the html, or remove all JS
        if (PoP_SSR_ServerUtils::includeScriptsAfterHtml() || PoP_WebPlatform_ServerUtils::disableJs()) {
            // Extract all <script> tags out, to be added once again after including jquery.js in the footer
            // $match[2] has the javascript code, without the <script tag
            $html = preg_replace_callback(
                '#<script(.*?)>(.*?)</script>#is',
                function ($match) {
                    self::$scripts[] = $match[2];
                    return '';
                },
                $html
            );
        }

        return $html;
    }
}

/**
 * Initialization
 */
PoP_ServerSideRendering_Utils::init();
