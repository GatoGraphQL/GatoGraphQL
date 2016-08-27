<?php /*global $gd_templatemanager;*/ ?>
<?php $engine = PoP_Engine_Factory::get_instance(); ?>
<?php $engine->check_redirect(true); ?>
<?php $engine->output_json(); ?>