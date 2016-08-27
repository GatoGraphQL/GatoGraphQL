<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_SIDEBARSECTION_OPINIONATEDVOTE', 'opinionatedvote');
define ('GD_COMPACTSIDEBARSECTION_OPINIONATEDVOTE', 'compact-opinionatedvote');

class VotingProcessors_FullViewSidebarSettings {
	
	public static function get_components($section) {

		$ret = array();

		switch ($section) {

			case GD_SIDEBARSECTION_OPINIONATEDVOTE:

				$ret[] = GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER;
				$ret[] = GD_TEMPLATE_WIDGET_POST_AUTHORS;
				break;

			case GD_COMPACTSIDEBARSECTION_OPINIONATEDVOTE:

				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_OPINIONATEDVOTELEFT;
				$ret[] = GD_TEMPLATE_SIDEBARMULTICOMPONENT_OPINIONATEDVOTERIGHT;
				break;
		}
		
		return $ret;
	}
}