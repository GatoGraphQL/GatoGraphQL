<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\EverythingElse\Misc\TagHelpers;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;

class PoP_TinyMCEHashtags
{
    public function __construct()
    {
        if (!is_admin()) {
            HooksAPIFacade::getInstance()->addFilter('mce_buttons', array($this, 'registerButton'));
            HooksAPIFacade::getInstance()->addFilter('mce_external_plugins', array($this, 'externalPlugins'));
            HooksAPIFacade::getInstance()->addFilter('teeny_mce_before_init', array($this, 'beforeInit'));
            HooksAPIFacade::getInstance()->addFilter('tiny_mce_before_init', array($this, 'beforeInit'));
        }
    }

    protected function configured()
    {
        return !empty(POP_COREPROCESSORS_HASHTAGS_EDITORACCESS);
    }

    public function registerButton($buttons)
    {
        if (!self::configured()) {
            return $buttons;
        }

        // Just before the Toggle Toolbar button
        // array_splice($buttons, array_search('wp_adv', $buttons), 0, array('hashtags'));
        // Place it at the beginning
        array_unshift($buttons, 'hashtags');
        return $buttons;
    }

    public function externalPlugins($plugins)
    {
        if (!self::configured()) {
            return $plugins;
        }

        $js_folder = POP_MASTERCOLLECTIONWEBPLATFORM_URL.'/js';

        $plugins['hashtags'] = $js_folder . '/tinymce/plugins/hashtags/plugin.js';

        return $plugins;
    }

    public function beforeInit($mceInit)
    {
        if (!self::configured()) {
            return $mceInit;
        }

        // Add the 'hashtags' settings
        // Get the name and description from all defined values
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $values = array();
        foreach (POP_COREPROCESSORS_HASHTAGS_EDITORACCESS as $tag_id) {
            $values[] = TagHelpers::getTagSymbolNameDescription($postTagTypeAPI->getTag($tag_id));
        }
        $title = HooksAPIFacade::getInstance()->applyFilters('PoP_TinyMCEHashtags:title', TranslationAPIFacade::getInstance()->__('#Hashtags', 'pop-coreprocessors'));
        $options = array(
            'values' => $values,
            'title' => $title,
        );
        $mceInit['hashtags'] = json_encode($options);

        return $mceInit;
    }
}

/**
 * Initialize
 */
new PoP_TinyMCEHashtags();
