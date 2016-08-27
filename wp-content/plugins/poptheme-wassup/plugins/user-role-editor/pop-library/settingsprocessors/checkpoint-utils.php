<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_STATIC', 'checkpoint-loggedinprofile-static');
define ('WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_DATAFROMSERVER', 'checkpoint-loggedinprofile-datafromserver');
define ('WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_CANEDIT', 'checkpoint-loggedinprofile-canedit');

define ('WASSUP_URE_CHECKPOINT_PROFILEORGANIZATION_STATIC', 'checkpoint-ure-profileorganization-static');
define ('WASSUP_URE_CHECKPOINT_PROFILEORGANIZATION_DATAFROMSERVER', 'checkpoint-ure-profileorganization-datafromserver');
define ('WASSUP_URE_CHECKPOINT_PROFILECOMMUNITY_STATIC', 'checkpoint-ure-profilecommunity-static');
define ('WASSUP_URE_CHECKPOINT_PROFILECOMMUNITY_DATAFROMSERVER', 'checkpoint-ure-profilecommunity-datafromserver');
define ('WASSUP_URE_CHECKPOINT_EDITMEMBERSHIP_DATAFROMSERVER', 'checkpoint-ure-editmembership-datafromserver');
define ('WASSUP_URE_CHECKPOINT_PROFILEINDIVIDUAL_DATAFROMSERVER', 'checkpoint-ure-profileindividual-datafromserver');

class Wassup_URE_SettingsProcessor_CheckpointUtils {

	public static function get_checkpoint($hierarchy, $name) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			$profile_checkpoints = array(
				GD_DATALOAD_CHECKPOINT_USERLOGGEDIN,
				GD_DATALOAD_CHECKPOINT_PROFILEACCESS,
			);
			$profileorganization_checkpoints = array_merge(
				$profile_checkpoints,
				array(
					GD_URE_DATALOAD_CHECKPOINT_ISPROFILEORGANIZATION
				)
			);
			$profilecommunity_checkpoints = array_merge(
				$profileorganization_checkpoints,
				array(
					GD_URE_DATALOAD_CHECKPOINT_ISCOMMUNITY
				)
			);
			$editmembership_checkpoints = array_merge(
				$profilecommunity_checkpoints,
				array(
					GD_URE_DATALOAD_CHECKPOINT_EDITINGCOMMUNITYMEMBER
				)
			);
			$profileindividual_checkpoints = array_merge(
				$profile_checkpoints,
				array(
					GD_URE_DATALOAD_CHECKPOINT_ISPROFILEINDIVIDUAL
				)
			);

			// Override the checkpoints from poptheme-wassup: whenever the user logged in checkpoint is requested,
			// add the further addition of checking that it is a profile
			$profile_static = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_USERLOGGEDIN_SUBMIT,
					GD_DATALOAD_CHECKPOINT_PROFILEACCESS_SUBMIT,
				),
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC
			);
			$profile_canedit = array(
				'checkpoints' => array(
					GD_DATALOAD_CHECKPOINT_USERLOGGEDIN,
					GD_DATALOAD_CHECKPOINT_PROFILEACCESS,
					GD_DATALOAD_CHECKPOINT_USERCANEDIT,
				),
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER
			);
			$profile_datafromserver = array(
				'checkpoints' => $profile_checkpoints,
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
			);
			$profileorganization_static = array(
				'checkpoints' => $profileorganization_checkpoints,
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC,
			);
			$profileorganization_datafromserver = array(
				'checkpoints' => $profileorganization_checkpoints,
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
			);
			$profilecommunity_static = array(
				'checkpoints' => $profilecommunity_checkpoints,
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC,
			);
			$profilecommunity_datafromserver = array(
				'checkpoints' => $profilecommunity_checkpoints,
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
			);
			$editmembership_datafromserver = array(
				'checkpoints' => $editmembership_checkpoints,
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
			);
			$profileindividual_datafromserver = array(
				'checkpoints' => $profileindividual_checkpoints,
				'type' => GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
			);

			switch ($name) {

				case WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_STATIC:
					return $profile_static;

				case WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_DATAFROMSERVER:
					return $profile_datafromserver;

				case WASSUP_URE_CHECKPOINT_LOGGEDINPROFILE_CANEDIT:
					return $profile_canedit;

				case WASSUP_URE_CHECKPOINT_PROFILEORGANIZATION_STATIC:
					return $profileorganization_static;

				case WASSUP_URE_CHECKPOINT_PROFILEORGANIZATION_DATAFROMSERVER:
					return $profileorganization_datafromserver;

				case WASSUP_URE_CHECKPOINT_PROFILECOMMUNITY_STATIC:
					return $profilecommunity_static;

				case WASSUP_URE_CHECKPOINT_PROFILECOMMUNITY_DATAFROMSERVER:
					return $profilecommunity_datafromserver;

				case WASSUP_URE_CHECKPOINT_EDITMEMBERSHIP_DATAFROMSERVER:
					return $editmembership_datafromserver;

				case WASSUP_URE_CHECKPOINT_PROFILEINDIVIDUAL_DATAFROMSERVER:
					return $profileindividual_datafromserver;
			}
		}
	
		return null;
	}
}