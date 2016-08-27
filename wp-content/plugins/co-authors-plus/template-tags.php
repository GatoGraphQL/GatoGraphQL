<?php

function get_coauthors( $post_id = 0 ) {
	global $post, $post_ID, $coauthors_plus, $wpdb;
	
	$coauthors = array();
	$post_id = (int)$post_id;
	if ( !$post_id && $post_ID )
		$post_id = $post_ID;
	if ( !$post_id && $post )
		$post_id = $post->ID;
	
	if ( $post_id ) {
		$coauthor_terms = get_the_terms( $post_id, $coauthors_plus->coauthor_taxonomy );
		
		if ( is_array( $coauthor_terms ) && !empty( $coauthor_terms ) ) {
			foreach( $coauthor_terms as $coauthor ) {
				$coauthor_slug = preg_replace( '#^cap\-#', '', $coauthor->slug );
				$post_author =  $coauthors_plus->get_coauthor_by( 'user_nicename', $coauthor_slug );
				// In case the user has been deleted while plugin was deactivated
				if ( !empty( $post_author ) )
					$coauthors[] = $post_author;
			}
		} else if ( !$coauthors_plus->force_guest_authors ) {
			// Hack GreenDrinks: for old posts, the plugin didn't save the Author in the terms, so it's missing
			// This creates a bug: for WPUF frontend, global $post is the page to edit the post, and not the post, so it takes that one
			// Ask before if there's a $post_id, since this one is the right one for the post
			if ( !is_admin()) {
				$post_from_post_id = get_post($post_id);
				$post_author = get_userdata( $post_from_post_id->post_author );
			}
			elseif ( $post ) {
				$post_author = get_userdata( $post->post_author );
			} else {
				$post_author = get_userdata( $wpdb->get_var( $wpdb->prepare("SELECT post_author FROM $wpdb->posts WHERE ID = %d", $post_id ) ) );
			}
			if ( !empty( $post_author ) )
				$coauthors[] = $post_author;
		} // the empty else case is because if we force guest authors, we don't ever care what value wp_posts.post_author has.
	}
	return $coauthors;
}

/**
 * Checks to see if the the specified user is author of the current global post or post (if specified)
 * @param object|int $user 
 * @param int $post_id
 */
function is_coauthor_for_post( $user, $post_id = 0 ) {
	global $post;
	
	if( ! $post_id && $post )
		$post_id = $post->ID;
	if( ! $post_id )
		return false;

	if ( ! $user )
		return false;
	
	$coauthors = get_coauthors( $post_id );
	if ( is_numeric( $user ) ) {
		$user = get_userdata( $user );
		$user = $user->user_login;
	}
	
	foreach( $coauthors as $coauthor ) {
		if ( $user == $coauthor->user_login || $user == $coauthor->linked_account )
			return true;
	}
	return false;
}

class CoAuthorsIterator {
	var $position = -1;
	var $original_authordata;
	var $current_author;
	var $authordata_array;
	var $count;
	
	function CoAuthorsIterator( $postID = 0 ){
		global $post, $authordata, $wpdb;
		$postID = (int)$postID;
		if( !$postID && $post )
			$postID = (int)$post->ID;
		if( !$postID )
			trigger_error(__('No post ID provided for CoAuthorsIterator constructor. Are you not in a loop or is $post not set?', 'co-authors-plus')); //return null;

		$this->original_authordata = $this->current_author = $authordata;
		$this->authordata_array = get_coauthors( $postID );
		
		$this->count = count($this->authordata_array);
	}
	
	function iterate(){
		global $authordata;
		$this->position++;
		
		//At the end of the loop
		if( $this->position > $this->count-1 ){
			$authordata = $this->current_author = $this->original_authordata;
			$this->position = -1;
			return false;
		}
		
		//At the beginning of the loop
		if( $this->position == 0 && !empty( $authordata ) )
			$this->original_authordata = $authordata;
		
		$authordata = $this->current_author = $this->authordata_array[$this->position];
		
		return true;
	}
	
	function get_position(){
		if ( $this->position === -1 )
			return false;
		return $this->position;
	}
	function is_last(){
		return  $this->position === $this->count-1;
	}
	function is_first(){
		return $this->position === 0;
	}
	function count(){
		return $this->count;
	}
	function get_all(){
		return $this->authordata_array;
	}
}

//Helper function for the following new template tags
function coauthors__echo( $tag, $type = 'tag', $separators = array(), $tag_args = null, $echo = true ) {

	// Define the standard output separator. Constant support is for backwards compat.
	// @see https://github.com/danielbachhuber/Co-Authors-Plus/issues/12
	$default_before = ( defined( 'COAUTHORS_DEFAULT_BEFORE' ) ) ? COAUTHORS_DEFAULT_BEFORE : '';
	$default_between = ( defined( 'COAUTHORS_DEFAULT_BETWEEN' ) ) ? COAUTHORS_DEFAULT_BETWEEN : ', ';
	$default_between_last = ( defined( 'COAUTHORS_DEFAULT_BETWEEN_LAST' ) ) ? COAUTHORS_DEFAULT_BETWEEN_LAST :  __( ' and ', 'co-authors-plus' );
	$default_after = ( defined( 'COAUTHORS_DEFAULT_AFTER' ) ) ? COAUTHORS_DEFAULT_AFTER : '';

	if( ! isset( $separators['before'] ) || $separators['before'] === NULL )
		$separators['before'] = apply_filters( 'coauthors_default_before', $default_before );
	if( ! isset( $separators['between'] ) || $separators['between'] === NULL )
		$separators['between'] = apply_filters( 'coauthors_default_between', $default_between );
	if( ! isset( $separators['betweenLast'] ) || $separators['betweenLast'] === NULL )
		$separators['betweenLast'] = apply_filters( 'coauthors_default_between_last', $default_between_last );
	if( ! isset( $separators['after'] ) || $separators['after'] === NULL )
		$separators['after'] = apply_filters( 'coauthors_default_after', $default_after );

	$output = '';
	
	$i = new CoAuthorsIterator();
	$output .= $separators['before'];
	$i->iterate();
	do {
		$author_text = '';
		
		if( $type == 'tag' )
			$author_text = $tag( $tag_args );
		elseif( $type == 'field' && isset( $i->current_author->$tag ) )
			$author_text = $i->current_author->$tag;
		elseif( $type == 'callback' && is_callable( $tag ) )
			$author_text = call_user_func( $tag, $i->current_author );
		
		// Fallback to user_login if we get something empty
		if( empty( $author_text ) )
			$author_text = $i->current_author->user_login;
		
		// Append separators
		if ( ! $i->is_first() && $i->count() > 2 )
			$output .= $separators['between'];
		
		if ( $i->is_last() && $i->count() > 1 ) {
			$output = rtrim( $output, $separators['between'] );
			$output .= $separators['betweenLast'];
		}
		
		$output .= $author_text;
	} while( $i->iterate() );
	
	$output .= $separators['after'];
	
	if( $echo )
		echo $output;
	
	return $output;
}

/**
 * Outputs the co-authors display names, without links to their posts.
 * Co-Authors Plus equivalent of the_author() template tag.
 *
 * @param string $between Delimiter that should appear between the co-authors
 * @param string $betweenLast Delimiter that should appear between the last two co-authors
 * @param string $before What should appear before the presentation of co-authors
 * @param string $after What should appear after the presentation of co-authors
 * @param bool $echo Whether the co-authors should be echoed or returned. Defaults to true.
 */
function coauthors( $between = null, $betweenLast = null, $before = null, $after = null, $echo = true ){
	return coauthors__echo('display_name', 'field', array(
		'between' => $between,
		'betweenLast' => $betweenLast,
		'before' => $before,
		'after' => $after
	), null, $echo );
}

/**
 * Outputs the co-authors display names, with links to their posts.
 * Co-Authors Plus equivalent of the_author_posts_link() template tag.
 *
 * @param string $between Delimiter that should appear between the co-authors
 * @param string $betweenLast Delimiter that should appear between the last two co-authors
 * @param string $before What should appear before the presentation of co-authors
 * @param string $after What should appear after the presentation of co-authors
 * @param bool $echo Whether the co-authors should be echoed or returned. Defaults to true.
 */
function coauthors_posts_links( $between = null, $betweenLast = null, $before = null, $after = null, $echo = true ){
	return coauthors__echo('coauthors_posts_links_single', 'callback', array(
		'between' => $between,
		'betweenLast' => $betweenLast,
		'before' => $before,
		'after' => $after
	), null, $echo );
}

/**
 * Outputs a single co-author linked to their post archive.
 *
 * @param object $author
 * @return string 
 */
function coauthors_posts_links_single( $author ) {
	$args = array(
		'before_html' => '',
		'href' => get_author_posts_url( $author->ID, $author->user_nicename ),
		'rel' => 'author',
		'title' => sprintf( __( 'Posts by %s', 'co-authors-plus' ), get_the_author() ),
		'class' => 'url fn',
		'text' => get_the_author(),
		'after_html' => ''
	);
	$args = apply_filters( 'coauthors_posts_link', $args, $author );
	$single_link = sprintf(
			'<a href="%1$s" title="%2$s" class="%3$s" rel="%4$s">%5$s</a>',
			esc_url( $args['href'] ),
			esc_attr( $args['title'] ),
			esc_attr( $args['class'] ),
			esc_attr( $args['rel'] ),
			esc_html( $args['text'] )
	);
	return $args['before_html'] . $single_link . $args['after_html'];
}

/**
 * Outputs the co-authors first names, without links to their posts.
 *
 * @param string $between Delimiter that should appear between the co-authors
 * @param string $betweenLast Delimiter that should appear between the last two co-authors
 * @param string $before What should appear before the presentation of co-authors
 * @param string $after What should appear after the presentation of co-authors
 * @param bool $echo Whether the co-authors should be echoed or returned. Defaults to true.
 */
function coauthors_firstnames($between = null, $betweenLast = null, $before = null, $after = null, $echo = true ){
	return coauthors__echo('get_the_author_meta', 'tag', array(
		'between' => $between,
		'betweenLast' => $betweenLast,
		'before' => $before,
		'after' => $after
	), 'first_name', $echo );
}

/**
 * Outputs the co-authors last names, without links to their posts.
 *
 * @param string $between Delimiter that should appear between the co-authors
 * @param string $betweenLast Delimiter that should appear between the last two co-authors
 * @param string $before What should appear before the presentation of co-authors
 * @param string $after What should appear after the presentation of co-authors
 * @param bool $echo Whether the co-authors should be echoed or returned. Defaults to true.
 */
function coauthors_lastnames($between = null, $betweenLast = null, $before = null, $after = null, $echo = true ) {
	return coauthors__echo('get_the_author_meta', 'tag', array(
		'between' => $between,
		'betweenLast' => $betweenLast,
		'before' => $before,
		'after' => $after
	), 'last_name', $echo );
}

/**
 * Outputs the co-authors nicknames, without links to their posts.
 *
 * @param string $between Delimiter that should appear between the co-authors
 * @param string $betweenLast Delimiter that should appear between the last two co-authors
 * @param string $before What should appear before the presentation of co-authors
 * @param string $after What should appear after the presentation of co-authors
 * @param bool $echo Whether the co-authors should be echoed or returned. Defaults to true.
 */
function coauthors_nicknames($between = null, $betweenLast = null, $before = null, $after = null, $echo = true ) {
	return coauthors__echo('get_the_author_meta', 'tag', array(
		'between' => $between,
		'betweenLast' => $betweenLast,
		'before' => $before,
		'after' => $after
	), 'nickname', $echo );
}

/**
 * Outputs the co-authors display names, with links to their websites if they've provided them.
 *
 * @param string $between Delimiter that should appear between the co-authors
 * @param string $betweenLast Delimiter that should appear between the last two co-authors
 * @param string $before What should appear before the presentation of co-authors
 * @param string $after What should appear after the presentation of co-authors
 * @param bool $echo Whether the co-authors should be echoed or returned. Defaults to true.
 */
function coauthors_links($between = null, $betweenLast = null, $before = null, $after = null, $echo = true ) {
	return coauthors__echo('coauthors_links_single', 'callback', array(
		'between' => $between,
		'betweenLast' => $betweenLast,
		'before' => $before,
		'after' => $after
	), null, $echo );
}

/**
 * Outputs the co-authors email addresses
 *
 * @param string $between Delimiter that should appear between the email addresses
 * @param string $betweenLast Delimiter that should appear between the last two email addresses
 * @param string $before What should appear before the presentation of email addresses
 * @param string $after What should appear after the presentation of email addresses
 * @param bool $echo Whether the co-authors should be echoed or returned. Defaults to true.
 */
function coauthors_emails($between = null, $betweenLast = null, $before = null, $after = null, $echo = true ) {
	return coauthors__echo('get_the_author_meta', 'tag', array(
		'between' => $between,
		'betweenLast' => $betweenLast,
		'before' => $before,
		'after' => $after
	), 'user_email', $echo );
}

/**
 * Outputs a single co-author, linked to their website if they've provided one.
 *
 * @param object $author
 * @return string 
 */
function coauthors_links_single( $author ) {
	if ( get_the_author_meta('url') ) {
		return sprintf( '<a href="%s" title="%s" rel="external">%s</a>',
			get_the_author_meta('url'),
			esc_attr( sprintf(__("Visit %s&#8217;s website"), get_the_author()) ),
			get_the_author()
		);
	} else {
		return get_the_author();
	}
}

/**
 * Outputs the co-authors IDs
 *
 * @param string $between Delimiter that should appear between the co-authors
 * @param string $betweenLast Delimiter that should appear between the last two co-authors
 * @param string $before What should appear before the presentation of co-authors
 * @param string $after What should appear after the presentation of co-authors
 * @param bool $echo Whether the co-authors should be echoed or returned. Defaults to true.
 */
function coauthors_IDs($between = null, $betweenLast = null, $before = null, $after = null, $echo = true ) {
	return coauthors__echo('ID', 'field', array(
		'between' => $between,
		'betweenLast' => $betweenLast,
		'before' => $before,
		'after' => $after
	), null, $echo );
}

function get_the_coauthor_meta( $field ) {
	global $wp_query, $post;
	
	$coauthors = get_coauthors();
	$meta = array();
	
	foreach( $coauthors as $coauthor ) {
		$user_id = $coauthor->ID;
		$meta[$user_id] = get_the_author_meta( $field, $user_id );
	}
	return $meta;
}

function the_coauthor_meta( $field, $user_id = 0 ) {
	// TODO: need before after options
	echo get_the_coauthor_meta($field, $user_id);
}

/**
 * List all the *co-authors* of the blog, with several options available.
 * optioncount (boolean) (false): Show the count in parenthesis next to the author's name.
 * show_fullname (boolean) (false): Show their full names.
 * hide_empty (boolean) (true): Don't show authors without any posts.
 * feed (string) (''): If isn't empty, show links to author's feeds.
 * feed_image (string) (''): If isn't empty, use this image to link to feeds.
 * echo (boolean) (true): Set to false to return the output, instead of echoing.
 * @param array $args The argument array.
 * @return null|string The output, if echo is set to false.
 */
function coauthors_wp_list_authors( $args = array() ) {
	global $coauthors_plus;

	$defaults = array(
		'optioncount'      => false,
		'show_fullname'    => false,
		'hide_empty'       => true,
		'feed'             => '',
		'feed_image'       => '',
		'feed_type'        => '',
		'echo'             => true,
		'style'            => 'list',
		'html'             => true,
		'number'           => 20, // A sane limit to start to avoid breaking all the things
	);

	$args = wp_parse_args( $args, $defaults );
	$return = '';

	$term_args = array(
			'orderby'      => 'name',
			'hide_empty'   => 0,
			'number'       => (int)$args['number'],
		);
	$author_terms = get_terms( $coauthors_plus->coauthor_taxonomy, $term_args );
	$authors = array();
	foreach( $author_terms as $author_term ) {
		// Something's wrong in the state of Denmark
		if ( false === ( $coauthor = $coauthors_plus->get_coauthor_by( 'user_login', $author_term->name ) ) )
			continue;

		$authors[$author_term->name] = $coauthor;
	
		$authors[$author_term->name]->post_count = $author_term->count;
	}

	foreach ( (array) $authors as $author ) {

		$link = '';

		if ( $args['show_fullname'] && ( $author->first_name && $author->last_name ) )
			$name = "$author->first_name $author->last_name";
		else
			$name = $author->display_name;


		if ( ! $args['html'] ) {
			if ( $author->post_count == 0 ) {
				if ( ! $args['hide_empty'] )
					$return .= $name . ', ';
			} else
				$return .= $name . ', ';

			// No need to go further to process HTML.
			continue;
		}

		if ( ! ( $author->post_count == 0 && $args['hide_empty'] ) && 'list' == $args['style'] )
			$return .= '<li>';
		if ( $author->post_count == 0 ) {
			if ( ! $args['hide_empty'] )
				$link = $name;
		} else {
			$link = '<a href="' . get_author_posts_url( $author->ID, $author->user_nicename ) . '" title="' . esc_attr( sprintf( __("Posts by %s", 'co-authors-plus' ), $name ) ) . '">' . esc_html( $name ) . '</a>';

			if ( (! empty( $args['feed_image'] ) ) || ( ! empty( $args['feed'] ) ) ) {
				$link .= ' ';
				if ( empty( $args['feed_image'] ) )
					$link .= '(';
				$link .= '<a href="' . get_author_feed_link( $author->ID ) . '"';

				if ( !empty( $args['feed'] ) ) {
					$title = ' title="' . esc_attr( $args['feed'] ) . '"';
					$alt = ' alt="' . esc_attr( $args['feed'] ) . '"';
					$name = $feed;
					$link .= $title;
				}

				$link .= '>';

				if ( ! empty( $args['feed_image'] ) )
					$link .= "<img src=\"" . esc_url( $args['feed_image'] ) . "\" style=\"border: none;\"$alt$title" . ' />';
				else
					$link .= $name;

				$link .= '</a>';

				if ( empty( $args['feed_image'] ) )
					$link .= ')';
			}

			if ( $args['optioncount'] )
				$link .= ' ('. $author->post_count . ')';

		}

		if ( ! ( $author->post_count == 0 && $args['hide_empty'] ) && 'list' == $args['style'] )
			$return .= $link . '</li>';
		else if ( ! $args['hide_empty'] )
			$return .= $link . ', ';
	}

	$return = trim( $return, ', ' );

	if ( ! $args['echo'] )
		return $return;
	echo $return;
}

/**
 * Retrieve a Co-Author's Avatar.
 *
 * Since Guest Authors doesn't enforce unique email addresses, simply loading the avatar by email won't work when
 * multiple Guest Authors share the same address.
 *
 * This is a replacement for using get_avatar(), which only operates on email addresses and cannot differentiate 
 * between Guest Authors (who may share an email) and regular user accounts
 * 
 * @param  object   $coauthor The Co Author or Guest Author object
 * @param  int      $size     The desired size
 * @return string             The image tag for the avatar, or an empty string if none could be determined
 */
function coauthors_get_avatar( $coauthor, $size = 32, $default = '', $alt = false ) {
	global $coauthors_plus;

	if ( ! is_object( $coauthor ) )
		return '';

	if ( isset( $coauthor->type ) && 'guest-author' == $coauthor->type ) {
		$guest_author_thumbnail = $coauthors_plus->guest_authors->get_guest_author_thumbnail( $coauthor, $size );

		if ( $guest_author_thumbnail )
			return $guest_author_thumbnail;
	}

	// Make sure we're dealing with an object for which we can retrieve an email
	if ( isset( $coauthor->user_email ) )
		return get_avatar( $coauthor->user_email, $size, $default, $alt );

	// Nothing matched, an invalid object was passed.
	return '';
}