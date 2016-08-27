<?php

/**---------------------------------------------------------------------------------------------------------------
 * cron.php
 * ---------------------------------------------------------------------------------------------------------------*/
// If CRON is disabled, then do not allowed the option 'cron' to get updated in the options table
// This is because it is an extremely slow query, which is executed on each single request!
// So disable it for PROD
if (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON) {
	add_filter('pre_update_option_cron', 'pop_disable_cron_update', 100000, 2);
}
function pop_disable_cron_update($value, $old_value) {

	// Simply return the $old_value, since then $value === $old_value, the UPDATE will exit
	return $old_value;
}