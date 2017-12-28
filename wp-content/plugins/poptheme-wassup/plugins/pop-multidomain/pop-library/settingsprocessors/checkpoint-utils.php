<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('WASSUP_CHECKPOINT_DOMAINVALID', 'checkpoint-domainvalid');

class Wassup_MultiDomain_SettingsProcessor_CheckpointUtils {

	public static function get_checkpoint($hierarchy, $name) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$domainvalid = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_DOMAINVALID,
				),
				// Adding type StateLess, so that the blockgroup (GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN) 
				// will have the right value of $block_checkpointvalidation_failed, in pop-engine.php, 
				// if the checkpoint fails, and it doesn't execute adding backgroundload-urls
				// Can't add type GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER because then it will also bring the user state,
				// which we don't want for page /initialize-domain/ since it produces bugs (eg: in GetPoP, the user can't be automatically logged-in
				// by calling /loggedinuser-data/ since that page may never be called) and it makes the page non-cacheable
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS
			);

			switch ($name) {

				case WASSUP_CHECKPOINT_DOMAINVALID:

					$checkpoint = $domainvalid;
					break;
			}
		}
	
		// Allow URE to add the extra checkpoint condition of the user having the Profile role
		$checkpoint = apply_filters('Wassup_MultiDomain_SettingsProcessor_CheckpointUtils:checkpoint', $checkpoint, $hierarchy, $name);

		// If there is no checkpoint, then $name failed to find it, which is a bug, so raise an exception
		if (!$checkpoint) {
			throw new Exception(sprintf('No checkpoint found with hierarchy \'%s\' and name \'%s\' (%s)', $hierarchy, $name, full_url()));
		}

		return $checkpoint;
	}
}