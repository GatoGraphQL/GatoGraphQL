<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Copied from plugin `hashtagger` (https://wordpress.org/plugins/hashtagger/)
 * Extracts #hashtags from the post and adds them as tags
 * Extracts @user_nicenames from the post and adds a notification for that user
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Mentions {

  protected $regex_general;
  protected $regex_users;
  
	public function __construct() {
    
    // $this->regex_users =    '/(^|[\s!\.:;\?(>])*@([\p{L}][\p{L}0-9_]+)(?=[^<>]*(?:<|$))/u';
    // $this->regex_users =    '/(^|[\s!\.:;\?(>])\@([\p{L}][\p{L}0-9_]+)(?=[^<>]*(?:<|$))/u';

    // Allow also underscore ("_")
    // Regex taken from https://stackoverflow.com/questions/13639478/how-do-i-extract-words-starting-with-a-hash-tag-from-a-string-into-an-array
    // $this->regex_general =  '/(?<!\w)#([A-Za-z0-9_]+)/u';
    // Regex taken from Twitter Hashtag validator: https://gist.github.com/janogarcia/3946583
    // Explanation:
    // * A hashtag can contain any UTF-8 alphanumeric character, plus the underscore symbol. That's expressed with the character class [0-9_\p{L}]*, based on http://stackoverflow.com/a/5767106/1441613
    // * A hashtag can't be only numeric, it must have at least one alpahanumeric character or the underscore symbol. That condition is checked by ([0-9_\p{L}]*[_\p{L}][0-9_\p{L}]*), similar to http://stackoverflow.com/a/1051998/1441613
    // * Finally, the modifier 'u' is added to ensure that the strings are treated as UTF-8
    // $this->regex_general =  '/(?<!\w)#([0-9_\p{L}]*[_\p{L}][0-9_\p{L}]*)/u';
    // Solution taken from a combination of:
      // 1. https://stackoverflow.com/questions/1563844/best-hashtag-regex:
      // Use: '/(?<=\s|^)#(\w*[A-Za-z_]+\w*)/';
      // 2. https://stackoverflow.com/questions/37920638/regex-pattern-to-match-hashtag-but-not-in-html-attributes
      // "Regex pattern to match hashtag, but not in HTML attributes"
      // /#[a-z0-9_]+(?![^<]*>)/
      // What the negative lookahead does is makes sure that there is a < between the hashtag and the next >.
    // So then I took the regex from #1, and applied the negative lookahead: +(?![^<]*>)
    $this->regex_general =  '/(?<=\s|^)#(\w*[A-Za-z_]+\w*)+(?![^<]*>)/';

    // Allow also underscore ("_"), dash ("-") and dots ("."), but only when they are not the final char (@pedro.perez. = pedro.perez). Eg: greenpeace-asia
    // Regex taken from https://stackoverflow.com/questions/13639478/how-do-i-extract-words-starting-with-a-hash-tag-from-a-string-into-an-array
    $this->regex_users =    '/(?<!\w)@([a-z0-9-._]+[a-z0-9])/iu';
    
    add_action('save_post', array($this, 'generate_post_tags'), 9999);
    add_action('wp_insert_comment', array($this, 'generate_comment_tags'), 9999, 2);
    
    if (!is_admin()) {
      // Can't use filter "the_content" because it doesn't work with page How to use website on MESYM
      // So quick fix: ignore for pages. Since the_content does not pass the post_id, we use another hook
      // Execute on 'pop_content_pre' so we do it before doing wpautop, or otherwise the hashtags after <p> don't work
      add_filter('pop_content_pre', array($this, 'process_content_post'), 9999, 2);      

      // Comment Leo 08/05/2016: Do not enable for excerpts, because somehow sometimes it fails (eg: with MESYM Documentary Night event) deleting everything
      // add_filter('pop_excerpt', array($this, 'process_content_post'), 9999, 2);      
      // Execute before wpautop
      add_filter('gd_comments_content', array($this, 'process_content'), 5);      
    }
  }
  
  // this function extracts the hashtags from content and adds them as tags to the post
  function generate_post_tags($post_id) {

    if (in_array(get_post_type($post_id), gd_dataload_posttypes())) {
    
      $content = get_post_field('post_content', $post_id);

      // $append = true because we will also add tags to this post extracted from comments posted under this post
      $tags = $this->get_hashtags_from_content($content);
      wp_set_post_tags($post_id, implode(', ', $tags), true);

      // Allow Events Manager to also add its own tags with its own taxonomy
      // This is needed so we can search using parameter 'tag' with events, using the common slug
      do_action('PoP_Mentions:post_tags:add', $post_id, $tags);

      // Extract all user_nicenames and notify them they were tagged
      // Get the previous ones, as to send an email only to the new ones
      $previous_taggedusers_ids = GD_MetaManager::get_post_meta($post_id, GD_METAKEY_POST_TAGGEDUSERS);

      // First delete all existing users, then add all new ones
      GD_MetaManager::delete_post_meta($post_id, GD_METAKEY_POST_TAGGEDUSERS);
      if ($user_nicenames = $this->get_user_nicenames_from_content($content)) {

        $taggedusers_ids = array();
        foreach ($user_nicenames as $user_nicename) {
          if ($user = get_user_by( 'slug', $user_nicename)) {
            $taggedusers_ids[] = $user->ID;
          }
        }

        if ($taggedusers_ids) {
          GD_MetaManager::update_post_meta($post_id, GD_METAKEY_POST_TAGGEDUSERS, $taggedusers_ids);

          // Send an email to all newly tagged users
          if ($newly_taggedusers_ids = array_diff($taggedusers_ids, $previous_taggedusers_ids)) {
            do_action('PoP_Mentions:post_tags:tagged_users', $post_id, $taggedusers_ids, $newly_taggedusers_ids);
          }
        }
      }
    }
  }

  function generate_comment_tags($comment_id, $comment) {

    $tags = $this->get_hashtags_from_content($comment->comment_content);

    // $append = true because the tags are added to the post from the comment
    wp_set_post_tags($comment->comment_post_ID, implode(', ', $tags), true);

    // Allow Events Manager to also add its own tags with its own taxonomy
    // This is needed so we can search using parameter 'tag' with events, using the common slug
    do_action('PoP_Mentions:post_tags:add', $comment->comment_post_ID, $tags);

    if ($user_nicenames = $this->get_user_nicenames_from_content($comment->comment_content)) {

      $taggedusers_ids = array();
      foreach ($user_nicenames as $user_nicename) {
        if ($user = get_user_by('slug', $user_nicename)) {
          $taggedusers_ids[] = $user->ID;
        }
      }

      if ($taggedusers_ids) {
        GD_MetaManager::update_comment_meta($comment_id, GD_METAKEY_COMMENT_TAGGEDUSERS, $taggedusers_ids);

        // Send an email to all newly tagged users
        do_action('PoP_Mentions:comment_tags:tagged_users', $comment_id, $taggedusers_ids);
      }
    }
  }
  
  // this function returns an array of hashtags from a given content - used by generate_tags()
  function get_hashtags_from_content( $content ) {
    preg_match_all( $this->regex_general, $content, $matches );
    return $matches[1];
  }
  
  // this function returns an array of user_nicenames from a given content - used by generate_tags()
  function get_user_nicenames_from_content( $content ) {
    preg_match_all( $this->regex_users, $content, $matches);
    return $matches[1];
  }
  
  // general function to process content
  function work( $content ) { 
    
    // Tags
    $content = str_replace( '##', '#', preg_replace_callback( $this->regex_general, array( $this, 'make_link_tag' ), $content ) );
    
    // Usernames
    $content = str_replace( '@@', '@', preg_replace_callback( $this->regex_users, array( $this, 'make_link_user_nicenames' ), $content ) );

    return $content;
  }
  
  // replace hashtags with links when displaying content
  // since v 3.0 post type depending
  function process_content( $content ) {
    
    // if (in_array(get_post_type(), gd_dataload_posttypes())) {
      $content = $this->work($content);
    // }
    return $content;
  }

  function process_content_post($content, $post_id) {
    
    if (in_array(get_post_type($post_id), gd_dataload_posttypes())) {
      $content = $this->work($content);
    }
    return $content;
  }
  
  // callback functions for preg_replace_callback used in content()
  function make_link_tag($match) {
    return $this->make_link($match);
  }
  function make_link_user_nicenames($match) {
    return $this->make_link_users($match);
  }
  
  // function to generate tag link
  private function make_link($match) {
    
    $tag = get_term_by('name', $match[1], 'post_tag');
    if (!$tag) {

      $content = $match[0];
    } 
    else {

      $content = sprintf(
        '<a class="hashtagger-tag" href="%s">%s</a>',
        get_tag_link($tag->term_id),
        $match[0]
      );
    }

    return $content;
  }

  // function to generate user link
  private function make_link_users( $match ) {

    // get by nickname or by login name
    $user = get_user_by('slug', $match[1]);
    if (!$user) {
      
      $content = $match[0];
    } 
    else {
      
      // Allow for the popover by adding data-popover-id
      $content = sprintf(
        '<a class="pop-mentions-user" data-popover-target="%s" href="%s">%s</a>',
        '#popover-'.GD_DATABASE_KEY_USERS.'-'.$user->ID,
        get_author_posts_url($user->ID),
        // Instead of the original match, use the user's name
        $user->display_name//$match[0]
      );

    }

    return $content;
  }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Mentions();
