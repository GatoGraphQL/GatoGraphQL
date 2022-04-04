<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\EverythingElse\Misc\TagHelpers;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;

class PoP_TinyMCEHashtags
{
    public function __construct()
    {
        if (!is_admin()) {
            \PoP\Root\App::addFilter('mce_buttons', $this->registerButton(...));
            \PoP\Root\App::addFilter('mce_external_plugins', $this->externalPlugins(...));
            \PoP\Root\App::addFilter('teeny_mce_before_init', $this->beforeInit(...));
            \PoP\Root\App::addFilter('tiny_mce_before_init', $this->beforeInit(...));
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
        $title = \PoP\Root\App::applyFilters('PoP_TinyMCEHashtags:title', TranslationAPIFacade::getInstance()->__('#Hashtags', 'pop-coreprocessors'));
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
