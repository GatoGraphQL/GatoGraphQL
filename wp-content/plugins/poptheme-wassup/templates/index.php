<?php

$engine = PoP_Engine_Factory::get_instance();
$engine->check_redirect(false);

get_header();

$vars = GD_TemplateManager_Utils::get_vars();
include $vars['theme-path'].'/mainpagesection.php';

get_footer();