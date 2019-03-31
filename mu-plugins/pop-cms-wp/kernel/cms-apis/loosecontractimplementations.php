<?php
namespace PoP\CMS\WP;

class CMSLooseContractImplementations
{
	function __construct() {
		
		$hooksapi = \PoP\CMS\HooksAPI_Factory::getInstance();

		// Actions
		$hooksapi->addAction('init', function() use($hooksapi) {
			$hooksapi->doAction('popcms:init');
		});
		$hooksapi->addAction('wp', function() use($hooksapi) {
			$hooksapi->doAction('popcms:beforeInit');
		});

		// The ones below go under some other plugin
		$hooksapi->addAction('wp_footer', function() use($hooksapi) {
			$hooksapi->doAction('popcms:footer');
		});
		$hooksapi->addAction('publish_to_draft', function($post) use($hooksapi) {
			$hooksapi->doAction('popcms:publishToDraft', $post);
		}, 10, 1);
		// $hooksapi->addAction('retrieve_password_key', function($user_login, $key) use($hooksapi) {
		// 	$hooksapi->doAction('popcms:retrievePasswordKey', $user_login, $key);
		// }, 10, 2);
		$hooksapi->addAction('save_post', function($post_id, $post, $update) use($hooksapi) {
			$hooksapi->doAction('popcms:savePost', $post_id, $post, $update);
		}, 10, 3);
		$hooksapi->addAction('delete_user', function($user_id, $reassign) use($hooksapi) {
			$hooksapi->doAction('popcms:deleteUser', $user_id, $reassign);
		}, 10, 2);
		$hooksapi->addAction('wp_insert_comment', function($comment_id, $comment) use($hooksapi) {
			$hooksapi->doAction('popcms:insertComment', $comment_id, $comment);
		}, 10, 2);
		$hooksapi->addAction('pending_to_publish', function($post) use($hooksapi) {
			$hooksapi->doAction('popcms:pendingToPublish', $post);
		}, 10, 1);
		$hooksapi->addAction('transition_post_status', function($new_status, $old_status, $post) use($hooksapi) {
			$hooksapi->doAction('popcms:transitionPostStatus', $new_status, $old_status, $post);
		}, 10, 3);
		$hooksapi->addAction('wp_enqueue_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:enqueueScripts');
		});
		$hooksapi->addAction('wp_print_footer_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printFooterScripts');
		});
		$hooksapi->addAction('wp_head', function() use($hooksapi) {
			$hooksapi->doAction('popcms:head');
		});
		$hooksapi->addAction('wp_print_styles', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printStyles');
		});
		$hooksapi->addAction('wp_print_scripts', function() use($hooksapi) {
			$hooksapi->doAction('popcms:printScripts');
		});
		$hooksapi->addAction('user_register', function($user_id) use($hooksapi) {
			$hooksapi->doAction('popcms:userRegister', $user_id);
		}, 10, 1);
		$hooksapi->addAction('spam_comment', function($comment_id, $comment) use($hooksapi) {
			$hooksapi->doAction('popcms:spamComment', $comment_id, $comment);
		}, 10, 2);
		$hooksapi->addAction('delete_comment', function($comment_id, $comment) use($hooksapi) {
			$hooksapi->doAction('popcms:deleteComment', $comment_id, $comment);
		}, 10, 2);

		$loosecontract_manager = \PoP\CMS\CMSLooseContract_Manager_Factory::getInstance();
		$loosecontract_manager->implementHooks([
			'popcms:init',
			'popcms:beforeInit',
			'popcms:footer',
			'popcms:publishToDraft',
			// 'popcms:retrievePasswordKey',
			'popcms:savePost',
			'popcms:deleteUser',
			'popcms:insertComment',
			'popcms:pendingToPublish',
			'popcms:transitionPostStatus',
			'popcms:enqueueScripts',
			'popcms:printFooterScripts',
			'popcms:head',
			'popcms:printStyles',
			'popcms:printScripts',
			'popcms:userRegister',
			'popcms:spamComment',
			'popcms:deleteComment',
		]);

		// Filters
		$hooksapi->addFilter('style_loader_src', function($src, $handle) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:styleSrc', $src, $handle);
		}, 10, 2);
		$hooksapi->addFilter('script_loader_src', function($src, $handle) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:scriptSrc', $src, $handle);
		}, 10, 2);
		$hooksapi->addFilter('the_title', function($post_title, $post_id) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:title', $post_title, $post_id);
		}, 10, 2);
		$hooksapi->addFilter('the_content', function($post_content) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:content', $post_content);
		}, 10, 1);
		$hooksapi->addFilter('excerpt_more', function($text) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:excerptMore', $text);
		}, 10, 1);
		$hooksapi->addFilter('login_url', function($url, $redirect) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:loginUrl', $url, $redirect);
		}, 10, 2);
		$hooksapi->addFilter('lostpassword_url', function($url, $redirect) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:lostPasswordUrl', $url, $redirect);
		}, 10, 2);
		$hooksapi->addFilter('logout_url', function($url, $redirect) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:logoutUrl', $url, $redirect);
		}, 10, 2);
		$hooksapi->addFilter('auth_cookie_expiration', function($time_in_seconds, $user_id, $remember) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:authCookieExpiration', $time_in_seconds, $user_id, $remember);
		}, 10, 3);
		$hooksapi->addFilter('script_loader_tag', function($tag, $handle, $src) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:scriptTag', $tag, $handle, $src);
		}, 10, 3);
		$hooksapi->addFilter('style_loader_tag', function($tag, $handle, $href, $media) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:styleTag', $tag, $handle, $href, $media);
		}, 10, 4);
		$hooksapi->addFilter('retrieve_password_title', function($title, $user_login, $user_data) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:retrievePasswordTitle', $title, $user_login, $user_data);
		}, 10, 3);
		$hooksapi->addFilter('retrieve_password_message', function($message, $key, $user_login, $user_data) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:retrievePasswordMessage', $message, $key, $user_login, $user_data);
		}, 10, 4);

		$loosecontract_manager->implementHooks([
			'popcms:styleSrc',
			'popcms:scriptSrc',
			'popcms:title',
			'popcms:content',
			'popcms:excerptMore',
			'popcms:loginUrl',
			'popcms:lostPasswordUrl',
			'popcms:logoutUrl',
			'popcms:authCookieExpiration',
			'popcms:scriptTag',
			'popcms:styleTag',
			'popcms:retrievePasswordTitle',
			'popcms:retrievePasswordMessage',
		]);

		$nameresolver = \PoP\CMS\NameResolver_Factory::getInstance();
		$nameresolver->implementNames([
			'popcms:capability:editPost' => 'edit_post',
			'popcms:capability:editPosts' => 'edit_posts',
			'popcms:capability:deletePages' => 'delete_pages',
			'popcms:option:limit' => 'posts_per_page',
			'popcms:option:dateFormat' => 'date_format',
			'popcms:option:charset' => 'blog_charset',
			'popcms:option:gmtOffset' => 'gmt_offset',
			'popcms:option:timezone' => 'timezone_string',
			'popcms:dbcolumn:orderby:comments:date' => 'comment_date_gmt',
			'popcms:dbcolumn:orderby:posts:date' => 'date',
			'popcms:dbcolumn:orderby:posts:modified' => 'modified',
			'popcms:dbcolumn:orderby:posts:id' => 'ID',
			'popcms:dbcolumn:orderby:tags:count' => 'count',
			'popcms:dbcolumn:orderby:tags:id' => 'term_id',
			'popcms:dbcolumn:orderby:users:name' => 'name',
			'popcms:dbcolumn:orderby:users:id' => 'ID',
			'popcms:dbcolumn:orderby:users:registrationdate' => 'registered',
		]);
	}
}

/**
 * Initialize
 */
new CMSLooseContractImplementations();

