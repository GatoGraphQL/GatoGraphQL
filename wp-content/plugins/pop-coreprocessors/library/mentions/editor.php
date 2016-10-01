<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Mentions functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('mce_external_plugins', 'gd_mentions_external_plugins' );
function gd_mentions_external_plugins($plugins) {

  if (is_admin()) {
    return $plugins;
  }

  $js_folder = POP_COREPROCESSORS_URI.'/js';
  $includes_js_folder = $js_folder.'/includes';

  // tinyMCE-mention plug-in: https://github.com/CogniStreamer/tinyMCE-mention

  // Comment Leo 24/03/2016: using custom.plugin.js instead of plugin.js because we made some hacks
  // $plugins['mention'] = $includes_js_folder . '/tinymce/plugins/mention/plugin.min.js';
  $plugins['mention'] = $includes_js_folder . '/tinymce/plugins/mention/custom.plugin.min.js';

  return $plugins;
}


add_filter('teeny_mce_before_init', 'gd_mentions_before_init' );
add_filter('tiny_mce_before_init', 'gd_mentions_before_init' );
function gd_mentions_before_init($mceInit) {

  if (is_admin()) {
    return $mceInit;
  }

  // Add the 'mentions' settings
  // Write the JSON encoded version directly, because mentions_source and mentions_insert are functions,
  // not strings, so we need no quotes around these values, but json_encode would add quotes
  // $mceInit['mentions'] = json_encode(array(
  //   'delimiter' => array('@', '#'),
  //   'source' => 'mentions_source',
  //   'insert' => 'mentions_insert',
  // ));
  $mceInit['mentions'] = sprintf(
    '{delimiter: %s, source: %s, insert: %s, render: %s, items: %s, queryBy: "%s"}',
    json_encode(array('@', '#')),
    'mentions_source',
    'mentions_insert',
    'mentions_render',
    8,
    'mention-queryby'
  );

  return $mceInit;
}

add_filter('gd_jquery_constants', 'gd_jquery_constants_mentions_manager_impl');
function gd_jquery_constants_mentions_manager_impl($jquery_constants) {

  global $gd_filter_manager, $gd_filtercomponent_name, $gd_filtercomponent_orderuser, $gd_filtercomponent_search, $gd_filtercomponent_ordertag;
  $query_wildcard = urlencode('%QUERY');

  $gd_filter_wildcardusers = $gd_filter_manager->get_filter(GD_FILTER_WILDCARDUSERS);
  $users_url = get_permalink(POP_WPAPI_PAGE_ALLUSERS);
  $gd_filter_wildcardtags = $gd_filter_manager->get_filter(GD_FILTER_WILDCARDTAGS);
  $tags_url = get_permalink(POP_WPAPI_PAGE_TAGS);
  
  // -------------------------------
  // User URL
  // -------------------------------
  $filter_params = array(
    $gd_filtercomponent_name->get_name() => $query_wildcard,
    
    // bring the posts ordering by comment count, so the more active users show first
    $gd_filtercomponent_orderuser->get_name() => 'post_count|DESC',
  );
  $users_fetchurl = $gd_filter_manager->add_filter_params($users_url, $gd_filter_wildcardusers, $filter_params);
  $users_fetchurl = GD_TemplateManager_Utils::add_jsonoutput_results_params($users_fetchurl, GD_TEMPLATEFORMAT_MENTION);

  // -------------------------------
  // User Prefetch URL
  // -------------------------------
  $filter_params = array(
    $gd_filtercomponent_orderuser->get_name() => 'post_count|DESC',
  );
  $users_baselineurl = $gd_filter_manager->add_filter_params($users_url, $gd_filter_wildcardusers, $filter_params);

  // Bring 10 times the pre-defined result set
  $users_baselineurl = add_query_arg(GD_URLPARAM_LIMIT, get_option('posts_per_page') * 10, $users_baselineurl);

  $users_baselineurl = GD_TemplateManager_Utils::add_jsonoutput_results_params($users_baselineurl, GD_TEMPLATEFORMAT_MENTION);

  // -------------------------------
  // Tags URL
  // -------------------------------
  $filter_params = array(
    $gd_filtercomponent_search->get_name() => $query_wildcard,
    
    // bring the posts ordering by count, so the more active tags show first
    $gd_filtercomponent_ordertag->get_name() => 'count|DESC',
  );
  $tags_fetchurl = $gd_filter_manager->add_filter_params($tags_url, $gd_filter_wildcardtags, $filter_params);
  $tags_fetchurl = GD_TemplateManager_Utils::add_jsonoutput_results_params($tags_fetchurl, GD_TEMPLATEFORMAT_MENTION);

  // -------------------------------
  // Tags Prefetch URL
  // -------------------------------
  $filter_params = array(
    $gd_filtercomponent_ordertag->get_name() => 'count|DESC',
  );
  $tags_baselineurl = $gd_filter_manager->add_filter_params($tags_url, $gd_filter_wildcardusers, $filter_params);

  // Bring 10 times the pre-defined result set
  $tags_baselineurl = add_query_arg(GD_URLPARAM_LIMIT, get_option('posts_per_page') * 10, $tags_baselineurl);

  $tags_baselineurl = GD_TemplateManager_Utils::add_jsonoutput_results_params($tags_baselineurl, GD_TEMPLATEFORMAT_MENTION);

  // -------------------------------
  // Settings
  // -------------------------------
  $jquery_constants['MENTIONS'] = array(
    'QUERYWILDCARD' => $query_wildcard,
    'TYPES' => array(
      '@' => array(
        'url' => $users_fetchurl,
        'baseline' => $users_baselineurl,
        'template' => GD_TEMPLATE_LAYOUTUSER_MENTION_COMPONENT,
        // Can't use "user-nicename", must use "nicename", because @Mentions plugin does not store the "-" in the html attribute, so it would
        // save the entry as data-usernicename. To avoid conflicts, just remove the "-"
        'key' => 'nicename',
      ),
      '#' => array(
       'url' => $tags_fetchurl,
       'baseline' => $tags_baselineurl,
       'template' => GD_TEMPLATE_LAYOUTTAG_MENTION_COMPONENT,
       'key' => 'namedescription',// 'name',
      ),
    ),
  );
      
  return $jquery_constants;
}

