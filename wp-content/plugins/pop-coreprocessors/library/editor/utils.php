<?php

class PoP_EditorUtils {

  // Comment Leo 02/03/2017: I thought the editor should be initialized first, but it seems it must go last, otherwise it doesn't work properly in the frontend (buttons look unstylish)
  // Has the TinyMCE editor been already initialized?
  // public static $initialized;

  public static function init() {

    // self::$initialized = false;

    // If no editor has been initialized, just create the main one, always, at the footer
    // This will be called for HTML output, not for JSON output
    add_action('wp_footer', array('PoP_EditorUtils', 'create_main_editor'));

    // Comment Leo 06/08/2017: moved under PoP_Core_DataLoad_NoncesCheckpointIOHandler_Hooks,
    // sending the nonces whenever the user is online
    // // Whenever loading the editor, on any page, add the nonces to the topLevelFeedback
    // add_action('media_buttons', array('PoP_EditorUtils', 'add_nonces'));
  }
  // public static function add_nonces() {

  //   // Whenever loading the editor, on any page, add the nonces to the topLevelFeedback
  //   add_filter('GD_DataLoad_TopLevelIOHandler:feedback', array('PoP_EditorUtils', 'add_nonces_to_feedback'));
  // }
  // public static function add_nonces_to_feedback($feedback) {

  //   // Nonces for validation for the Media Manager
  //   $feedback[GD_URLPARAM_NONCES] = array(
  //     'media-form' => wp_create_nonce('media-form'),
  //     'media-send-to-editor' => wp_create_nonce('media-send-to-editor'),
  //   );

  //   return $feedback;
  // }

  public static function create_main_editor() {

    // Always create the main editor, which will have all the TinyMCE properties to be
    // replicated into all TinyMCE editors in the frontend
    // if (!self::$initialized) {

    //   self::$initialized = true;

      ob_start();
      wp_editor('', GD_TEMPLATESETTINGS_EDITOR_NAME);
      ob_get_clean();
    // }
  }

  public static function get_editor_code($editor_id, $value = '', $options = array()) {

    // Make sure to create the main editor before creating any other editor
    // self::create_main_editor();

    ob_start();
    wp_editor($value, $editor_id, $options);
    return ob_get_clean();
  }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
PoP_EditorUtils::init();