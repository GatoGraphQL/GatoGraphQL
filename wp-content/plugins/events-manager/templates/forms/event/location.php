<?php
global $EM_Event;
$required = apply_filters('em_required_html','<i>*</i>');
?>
<?php if( !get_option('dbem_require_location') && !get_option('dbem_use_select_for_locations') ): ?>
<div class="em-location-data-nolocation">
	<p>
		<input type="checkbox" name="no_location" id="no-location" value="1" <?php if( !empty($EM_Event->event_id) && ($EM_Event->location_id === '0' || $EM_Event->location_id === 0) ) echo 'checked="checked"'; ?>>
		<?php _e('This event does not have a physical location.','events-manager'); ?>
	</p>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$('#no-location').change(function(){
				if( $('#no-location').is(':checked') ){
					$('#em-location-data').hide();
				}else{
					$('#em-location-data').show();
				}
			}).trigger('change');
		});
	</script>
</div>
<?php endif; ?>
<div id="em-location-data" class="em-location-data">
	<div id="location_coordinates" style='display: none;'>
		<input id='location-latitude' name='location_latitude' type='text' value='<?php echo $EM_Event->get_location()->location_latitude; ?>' size='15' />
		<input id='location-longitude' name='location_longitude' type='text' value='<?php echo $EM_Event->get_location()->location_longitude; ?>' size='15' />
	</div>
	<?php if( get_option('dbem_use_select_for_locations') || !$EM_Event->can_manage('edit_locations','edit_others_locations') ) : ?> 
	<table class="em-location-data">
		<tr class="em-location-data-select">
			<th><?php _e('Location:','events-manager') ?> </th>
			<td> 
				<select name="location_id" id='location-select-id' size="1">  
					<?php if(!get_option('dbem_require_location',true)): ?><option value="0"><?php _e('No Location','events-manager'); ?></option><?php endif; ?>
					<?php 
					$ddm_args = array('blog'=>false, 'private'=>$EM_Event->can_manage('read_private_locations'));
					$ddm_args['owner'] = (is_user_logged_in() && !current_user_can('read_others_locations')) ? get_current_user_id() : false;
					$locations = EM_Locations::get($ddm_args);
					$selected_location = !empty($EM_Event->location_id) || !empty($EM_Event->event_id) ? $EM_Event->location_id:get_option('dbem_default_location');
					foreach($locations as $EM_Location) {
						$selected = ($selected_location == $EM_Location->location_id) ? "selected='selected' " : '';
				   		?>          
				    	<option value="<?php echo $EM_Location->location_id ?>" title="<?php echo "{$EM_Location->location_latitude},{$EM_Location->location_longitude}" ?>" <?php echo $selected ?>><?php echo $EM_Location->location_name; ?></option>
				    	<?php
					}
					?>
				</select>
			</td>
		</tr>
	</table>
	<?php else : ?>
	<table class="em-location-data">
		<?php 
			global $EM_Location;
			if( $EM_Event->location_id !== 0 ){
				$EM_Location = $EM_Event->get_location();
			}elseif(get_option('dbem_default_location') > 0){
				$EM_Location = em_get_location(get_option('dbem_default_location'));
			}else{
				$EM_Location = new EM_Location();
			}
		?>
		<tr class="em-location-data-name">
			<th><?php _e ( 'Location Name:', 'events-manager')?></th>
			<td>
				<input id='location-id' name='location_id' type='hidden' value='<?php echo $EM_Location->location_id; ?>' size='15' />
				<input id="location-name" type="text" name="location_name" value="<?php echo esc_attr($EM_Location->location_name, ENT_QUOTES); ?>" /><?php echo $required; ?>													
				<br />
				<em id="em-location-search-tip"><?php _e( 'Create a location or start typing to search a previously created location.', 'events-manager')?></em>
				<em id="em-location-reset" style="display:none;"><?php _e('You cannot edit saved locations here.', 'events-manager'); ?> <a href="#"><?php _e('Reset this form to create a location or search again.', 'events-manager')?></a></em>
			</td>
 		</tr>
		<tr class="em-location-data-address">
			<th><?php _e ( 'Address:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-address" type="text" name="location_address" value="<?php echo esc_attr($EM_Location->location_address, ENT_QUOTES); ; ?>" /><?php echo $required; ?>
			</td>
		</tr>
		<tr class="em-location-data-town">
			<th><?php _e ( 'City/Town:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-town" type="text" name="location_town" value="<?php echo esc_attr($EM_Location->location_town, ENT_QUOTES); ?>" /><?php echo $required; ?>
			</td>
		</tr>
		<tr class="em-location-data-state">
			<th><?php _e ( 'State/County:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-state" type="text" name="location_state" value="<?php echo esc_attr($EM_Location->location_state, ENT_QUOTES); ?>" />
			</td>
		</tr>
		<tr class="em-location-data-postcode">
			<th><?php _e ( 'Postcode:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-postcode" type="text" name="location_postcode" value="<?php echo esc_attr($EM_Location->location_postcode, ENT_QUOTES); ?>" />
			</td>
		</tr>
		<tr class="em-location-data-region">
			<th><?php _e ( 'Region:', 'events-manager')?>&nbsp;</th>
			<td>
				<input id="location-region" type="text" name="location_region" value="<?php echo esc_attr($EM_Location->location_region, ENT_QUOTES); ?>" />
			</td>
		</tr>
		<tr class="em-location-data-country">
			<th><?php _e ( 'Country:', 'events-manager')?>&nbsp;</th>
			<td>
				<select id="location-country" name="location_country">
					<option value="0" <?php echo ( $EM_Location->location_country == '' && $EM_Location->location_id == '' && get_option('dbem_location_default_country') == '' ) ? 'selected="selected"':''; ?>><?php _e('none selected','events-manager'); ?></option>
					<?php foreach(em_get_countries() as $country_key => $country_name): ?>
					<option value="<?php echo $country_key; ?>" <?php echo ( $EM_Location->location_country == $country_key || ($EM_Location->location_country == '' && $EM_Location->location_id == '' && get_option('dbem_location_default_country')==$country_key) ) ? 'selected="selected"':''; ?>><?php echo $country_name; ?></option>
					<?php endforeach; ?>
				</select><?php echo $required; ?>
			</td>
		</tr>
	</table>
	<?php endif; ?>
	<?php if ( get_option( 'dbem_gmap_is_active' ) ) em_locate_template('forms/map-container.php',true); ?>
	<br style="clear:both;" />
</div>