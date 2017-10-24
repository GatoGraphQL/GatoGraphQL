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
				// Adding type DataFromServer, so that the blockgroup (GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN) 
				// will have the right value of $block_checkpointvalidation_failed, in pop-engine.php, 
				// if the checkpoint fails, and it doesn't execute adding backgroundload-urls
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER
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