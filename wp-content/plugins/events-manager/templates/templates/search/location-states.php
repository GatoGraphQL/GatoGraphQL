<?php $args = !empty($args) ? $args:array(); /* @var $args array */ ?>
<!-- START State/County Search -->
<div class="em-search-state em-search-field">
	<label><?php echo esc_html($args['state_label']); ?></label>
	<select name="state" class="em-search-state em-events-search-state">
		<option value=''><?php echo esc_html(get_option('dbem_search_form_states_label')); ?></option>
		<?php
		global $wpdb;
		$em_states = $cond = array();
		if( !empty($args['country']) ) $cond[] = $wpdb->prepare("AND location_country=%s", $args['country']);
		if( !empty($args['region']) ) $cond[] =  $wpdb->prepare("AND location_region=%s", $args['region']);
		if( !empty($cond) || empty($args['search_countries']) ){ //get specific states, whether restricted by country/region or all states if no country field is displayed
			$em_states = $wpdb->get_results("SELECT DISTINCT location_state FROM ".EM_LOCATIONS_TABLE." WHERE location_state IS NOT NULL AND location_state != '' AND location_status=1 ".implode(' ', $cond)." ORDER BY location_state", ARRAY_N);
		}
		foreach($em_states as $state){
			?>
			 <option<?php echo (!empty($args['state']) && $args['state'] == $state[0]) ? ' selected="selected"':''; ?>><?php echo esc_html($state[0]); ?></option>
			<?php 
		}
		?>
	</select>
</div>
<!-- END State/County Search -->