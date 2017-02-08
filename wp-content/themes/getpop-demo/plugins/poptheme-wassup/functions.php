<?php

// Do not add the menu in the background
add_filter('PoPTheme_Wassup_Utils:add_background_menu', 'getpopdemo_processors_remove_background_menu');
function getpopdemo_processors_remove_background_menu($add) {

	return false;
}