/*--------------------------------------------------------------------------------
 * Custom Media Manager / JWP6
 *--------------------------------------------------------------------------------*/

function gd_jwp6_media_added(win) {

	// Trigger
	jQuery(document).triggerHandler('gd_jwp6_media_manager_closed');

	// Remove window
	win.tb_remove();
}

