<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Timezone
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// The timezone is originally set to UTC, we must set it to the chosen Timezone (eg: Asia/Kuala Lumpur)
// http://wordpress.org/support/topic/why-does-wordpress-set-timezone-to-utc
date_default_timezone_set( get_option( 'timezone_string' ) );