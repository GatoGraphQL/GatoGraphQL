<?php

add_filter('GD_Core_Template_Processor_Blocks:jsmethod:latestcounts', 'pop_sw_latestcountsjsmethods_resettimestamp');
function pop_sw_latestcountsjsmethods_resettimestamp($jsmethods) {

	$jsmethods[] = 'resetTimestamp';
	return $jsmethods;
}