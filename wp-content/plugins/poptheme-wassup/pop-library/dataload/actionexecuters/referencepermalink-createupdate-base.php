<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_ActionExecuter_CreateUpdate_ReferencePermalinkBase extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {

	function get_success_string($post_id, $status) {

		if ($status == 'publish') {

			// Give a link to the referenced post to the opinionatedvote, and force it to get it from the server again
			$references = GD_MetaManager::get_post_meta($post_id, GD_METAKEY_POST_REFERENCES);
			$success_string = sprintf(
				__('<a href="%s" %s>Click here to view it</a>.', 'poptheme-wassup'), 
				get_permalink($references[0]),
				get_reloadurl_linkattrs()
			);
		}
		elseif ($status == 'draft') {
			
			$success_string = __('Please note that the status is still "Draft", so the post is not online yet.', 'poptheme-wassup');
		}

		return apply_filters('gd-createupdate-uniquereference:execute:successstring', $success_string, $post_id, $status);
	}
}