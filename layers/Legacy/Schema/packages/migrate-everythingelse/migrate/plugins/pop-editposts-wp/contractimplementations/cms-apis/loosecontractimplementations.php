<?php
namespace PoP\EditPosts\WP;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;

class CMSLooseContractImplementations
{
	public function __construct() {
		
		$hooksapi = \PoP\Root\App::getHookManager();

		// Actions
		$hooksapi->addAction('publish_to_draft', function($post) use($hooksapi) {
			$hooksapi->doAction('popcms:publishToDraft', $post);
		}, 10, 1);
		$hooksapi->addAction('save_post', function($post_id, $post, $update) use($hooksapi) {
			$hooksapi->doAction('popcms:savePost', $post_id, $post, $update);
		}, 10, 3);
		$hooksapi->addAction('pending_to_publish', function($post) use($hooksapi) {
			$hooksapi->doAction('popcms:pendingToPublish', $post);
		}, 10, 1);
		$hooksapi->addAction('transition_post_status', function($new_status, $old_status, $post) use($hooksapi) {
			$hooksapi->doAction('popcms:transitionPostStatus', $new_status, $old_status, $post);
		}, 10, 3);

		$loosecontract_manager = LooseContractManagerFacade::getInstance();
		$loosecontract_manager->implementHooks([
			'popcms:publishToDraft',
			'popcms:savePost',
			'popcms:pendingToPublish',
			'popcms:transitionPostStatus',
		]);

		// Filters.
		$hooksapi->addFilter('get_edit_post_link', function($link, $post_id) use($hooksapi) {
			return $hooksapi->applyFilters('popcms:editPostLink', $link, $post_id);
		}, 10, 2);

		$loosecontract_manager->implementHooks([
			'popcms:editPostLink',
		]);
	}
}

/**
 * Initialize
 */
new CMSLooseContractImplementations();

