<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('WASSUP_CHECKPOINT_NEVERCACHE', 'checkpoint-nevercache');
define ('WASSUP_CHECKPOINT_NOTLOGGEDIN', 'checkpoint-notloggedin');
define ('WASSUP_CHECKPOINT_LOGGEDIN_STATIC', 'checkpoint-loggedin-static');
define ('WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER', 'checkpoint-loggedin-datafromserver');
define ('WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT', 'checkpoint-loggedin-canedit');
define ('WASSUP_CHECKPOINT_LOGGEDIN_ISADMINISTRATOR', 'checkpoint-loggedin-isadministrator');
define ('WASSUP_CHECKPOINT_DOMAINVALID', 'checkpoint-domainvalid');

class Wassup_SettingsProcessor_CheckpointUtils {

	public static function get_checkpoint($hierarchy, $name) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			// Hack: just by having the type GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
			// these pages will never by cached by WP Super Cache, even if no checkpoints needed
			// However, add one (fake) checkpoint, so that function get_user_info($template_id, $atts) in toplevels-base.php still concludes the user log in information must be brought in
			// Comment Leo 10/03/2016: added type GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER just for the nevercache type of pages, which need to checkpoint validation
			// Comment Leo 11/01/2017: no need to add the alwaystrue checkpoint anymore, since page_requires_user_state (depended by get_user_info($template_id, $atts)) also check the validation type, not just if it has checkpoints or not
			$nevercache = array(
				'checkpoints' => array(
					// GD_DATALOAD_CHECKPOINT_ALWAYSTRUE,
				),
				'type' => GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER,//GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
			);
			$notloggedin = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_USERNOTLOGGEDIN_SUBMIT
				),
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC,
			);
			// The _SUBMIT checkpoints are evaluated only when doing 'POST'. This way, it won't give the "You're not logged in" error
			// when clicking on Add Project when the user is still not logged in, and it also avoids another problem: through replicable, it keeps
			// the initial settings, so if loading when user is not logged in, then he logs in, then clicks on Add Project, it would still show the "You're not logged in" error msg
			$loggedin_static = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_USERLOGGEDIN_SUBMIT,
					// GD_DATALOAD_CHECKPOINT_PROFILEACCESS_SUBMIT,
				),
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC
			);
			$loggedin_datafromserver = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_USERLOGGEDIN,
					// GD_DATALOAD_CHECKPOINT_PROFILEACCESS,
				),
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER
			);
			$loggedin_canedit = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_USERLOGGEDIN,
					// GD_DATALOAD_CHECKPOINT_PROFILEACCESS,
					GD_DATALOAD_CHECKPOINT_NONCE,
					GD_DATALOAD_CHECKPOINT_USERCANEDIT,
				),
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER
			);
			$loggedin_isadmin = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_USERLOGGEDIN,
					// GD_DATALOAD_CHECKPOINT_PROFILEACCESS,
					GD_DATALOAD_CHECKPOINT_ISADMINISTRATOR,
				),
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER
			);
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

				case WASSUP_CHECKPOINT_NEVERCACHE:
					$checkpoint = $nevercache;
					break;

				case WASSUP_CHECKPOINT_NOTLOGGEDIN:
					$checkpoint = $notloggedin;
					break;

				case WASSUP_CHECKPOINT_LOGGEDIN_STATIC:
					$checkpoint = $loggedin_static;
					break;

				case WASSUP_CHECKPOINT_LOGGEDIN_DATAFROMSERVER:
					$checkpoint = $loggedin_datafromserver;
					break;

				case WASSUP_CHECKPOINT_LOGGEDIN_CANEDIT:
					$checkpoint = $loggedin_canedit;
					break;

				case WASSUP_CHECKPOINT_LOGGEDIN_ISADMINISTRATOR:
					$checkpoint = $loggedin_isadmin;
					break;

				case WASSUP_CHECKPOINT_DOMAINVALID:

					$checkpoint = $domainvalid;
					break;
			}
		}
	
		// Allow URE to add the extra checkpoint condition of the user having the Profile role
		$checkpoint = apply_filters('Wassup_SettingsProcessor_CheckpointUtils:checkpoint', $checkpoint, $hierarchy, $name);

		// If there is no checkpoint, then $name failed to find it, which is a bug, so raise an exception
		if (!$checkpoint) {
			throw new Exception(sprintf('No checkpoint found with hierarchy \'%s\' and name \'%s\' (%s)', $hierarchy, $name, full_url()));
		}

		return $checkpoint;
	}
}