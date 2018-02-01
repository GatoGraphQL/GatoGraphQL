<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Hashtags TinyMCE plug-in
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_TinyMCEHashtags {

  public function __construct() {
    
    if (!is_admin()) {
      add_filter('mce_buttons', array($this, 'register_button'));
      add_filter('mce_external_plugins', array($this, 'external_plugins'));
      add_filter('teeny_mce_before_init', array($this, 'before_init'));
      add_filter('tiny_mce_before_init', array($this, 'before_init'));
    }
  }

  protected function configured() {
  
    return !empty(POP_COREPROCESSORS_HASHTAGS_EDITORACCESS);
  }

  function register_button($buttons) {

    if (!self::configured()) {
      return $buttons;
    }
  
    // Just before the Toggle Toolbar button
    // array_splice($buttons, array_search('wp_adv', $buttons), 0, array('hashtags'));
    // Place it at the beginning
    array_unshift($buttons, 'hashtags');
    return $buttons;
  }

  function external_plugins($plugins) {

    if (!self::configured()) {
      return $plugins;
    }

    $js_folder = POP_COREPROCESSORS_URL.'/js';

    $plugins['hashtags'] = $js_folder . '/tinymce/plugins/hashtags/plugin.js';

    return $plugins;
  }

  function before_init($mceInit) {

    if (!self::configured()) {
      return $mceInit;
    }

    // Add the 'hashtags' settings
    // Get the name and description from all defined values
    $values = array();
    foreach (POP_COREPROCESSORS_HASHTAGS_EDITORACCESS as $tag_id) {
      $values[] = PoP_TagUtils::get_tag_namedescription(get_tag($tag_id), true);
    }
    $title = apply_filters('PoP_TinyMCEHashtags:title', __('#Hashtags', 'pop-coreprocessors'));
    $options = array(
      'values' => $values,
      'title' => $title,
    );
    $mceInit['hashtags'] = json_encode($options);

    return $mceInit;
  }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_TinyMCEHashtags();
