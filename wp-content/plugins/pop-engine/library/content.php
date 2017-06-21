<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Content functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

function gd_get_document_title() {
	$site_name = get_bloginfo('name');
	$separator = '|';
	
  $vars = GD_TemplateManager_Utils::get_vars();
	if ( $vars['global-state']['is-single']/*is_single()*/ ) {
      $content = single_post_title('', FALSE);
  }
  elseif ( $vars['global-state']['is-home']/*is_home()*/ || $vars['global-state']['is-front-page']/*is_front_page()*/ ) { 
    $content = get_bloginfo('description');
  }
  elseif ( $vars['global-state']['is-page']/*is_page()*/ ) { 
    $content = single_post_title('', FALSE); 
  }
  elseif ( $vars['global-state']['is-search']/*is_search()*/ ) { 
    $content = __('Search:', 'pop-engine'); 
    $content .= ' ' . esc_html(stripslashes(get_search_query()), true);
  }
  elseif ( $vars['global-state']['is-category']/*is_category()*/ ) {
    $content = single_cat_title("", false);
  }
  elseif ( $vars['global-state']['is-tag']/*is_tag()*/ ) { 
    // $content = gd_content_tag_query();
    $content = PoP_TagUtils::get_tag_symbol().single_tag_title("", false);
  }
  elseif ( $vars['global-state']['is-404']/*is_404()*/ ) { 
    $content = __('Ops, nothing found here!', 'pop-engine'); 
  }
  elseif ( $vars['global-state']['is-author']/*is_author()*/ ) { 
    $author = $vars['global-state']['author']/*global $author*/;
    $curauth = get_userdata($author);
    $content = $curauth->display_name; 
  }
  else { 
    $content = get_bloginfo('description');
  }

  if (get_query_var('paged')) {
    $content .= ' ' .$separator. ' ';
    $content .= 'Page';
    $content .= ' ';
    $content .= get_query_var('paged');
  }

  if($content) {
    if ( $vars['global-state']['is-home']/*is_home()*/ || $vars['global-state']['is-front-page']/*is_front_page()*/ ) {
        $elements = array(
          'site_name' => $site_name,
          'separator' => $separator,
          'content' => $content
        );
    }
    else {
        $elements = array(
          'content' => $content
        );
    }  
  } else {
    $elements = array(
      'site_name' => $site_name
    );
  }

  // Filters should return an array
  $elements = apply_filters('gd_get_document_title:elements', $elements);

  // But if they don't, it won't try to implode
  if(is_array($elements)) {
    $doctitle = implode(' ', $elements);
  }
  else {
    $doctitle = $elements;
  }
    
	return $doctitle;
}

function gd_get_favicon() {
  
  return apply_filters('gd_get_favicon', home_url().'/favicon.ico');
}
