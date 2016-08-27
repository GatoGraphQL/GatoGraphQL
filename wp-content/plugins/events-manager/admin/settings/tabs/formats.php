<?php if( !function_exists('current_user_can') || !current_user_can('manage_options') ) return; ?>
<!-- FORMAT OPTIONS -->
<div class="em-menu-formats em-menu-group"  <?php if( !defined('EM_SETTINGS_TABS') || !EM_SETTINGS_TABS) : ?>style="display:none;"<?php endif; ?>>				
	<div  class="postbox " id="em-opt-events-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Events', 'events-manager'); ?> </span></h3>
	<div class="inside">
    	<table class="form-table">
		 	<tr class="em-header"><td colspan="2">
		 		<h4><?php echo sprintf(__('%s Page','events-manager'),__('Events','events-manager')); ?></h4>
		 		<p><?php _e('These formats will be used on your events page. This will also be used if you do not provide specified formats in other event lists, like in shortcodes.','events-manager'); ?></p>
		 	</td></tr>
			<?php
			$grouby_modes = array(0=>__('None','events-manager'), 'yearly'=>__('Yearly','events-manager'), 'monthly'=>__('Monthly','events-manager'), 'weekly'=>__('Weekly','events-manager'), 'daily'=>__('Daily','events-manager'));
			em_options_select(__('Events page grouping','events-manager'), 'dbem_event_list_groupby', $grouby_modes, __('If you choose a group by mode, your events page will display events in groups of your chosen time range.','events-manager'));
			em_options_input_text(__('Events page grouping header','events-manager'), 'dbem_event_list_groupby_header_format', __('Choose how to format your group headings.','events-manager').' '. sprintf(__('#s will be replaced by the date format below', 'events-manager'), 'http://codex.wordpress.org/Formatting_Date_and_Time'));
			em_options_input_text(__('Events page grouping date format','events-manager'), 'dbem_event_list_groupby_format', __('Choose how to format your group heading dates. Leave blank for default.','events-manager').' '. sprintf(__('Date and Time formats follow the <a href="%s">WordPress time formatting conventions</a>', 'events-manager'), 'http://codex.wordpress.org/Formatting_Date_and_Time'));
			em_options_textarea ( __( 'Default event list format header', 'events-manager'), 'dbem_event_list_item_format_header', __( 'This content will appear just above your code for the default event list format. Default is blank', 'events-manager') );
		 	em_options_textarea ( __( 'Default event list format', 'events-manager'), 'dbem_event_list_item_format', __( 'The format of any events in a list.', 'events-manager').$events_placeholder_tip );
			em_options_textarea ( __( 'Default event list format footer', 'events-manager'), 'dbem_event_list_item_format_footer', __( 'This content will appear just below your code for the default event list format. Default is blank', 'events-manager') );
			em_options_input_text ( __( 'No events message', 'events-manager'), 'dbem_no_events_message', __( 'The message displayed when no events are available.', 'events-manager') );
			em_options_input_text ( __( 'List events by date title', 'events-manager'), 'dbem_list_date_title', __( 'If viewing a page for events on a specific date, this is the title that would show up. To insert date values, use <a href="http://www.php.net/manual/en/function.date.php">PHP time format characters</a>  with a <code>#</code> symbol before them, i.e. <code>#m</code>, <code>#M</code>, <code>#j</code>, etc.<br/>', 'events-manager') );
			?>
		 	<tr class="em-header">
		 	    <td colspan="2">
		 	        <h4><?php echo sprintf(__('Single %s Page','events-manager'),__('Event','events-manager')); ?></h4>
		 	        <em><?php echo sprintf(__('These formats can be used on %s pages or on other areas of your site displaying an %s.','events-manager'),__('event','events-manager'),__('event','events-manager'));?></em>
		 	</tr>
		 	<?php
			if( EM_MS_GLOBAL && !get_option('dbem_ms_global_events_links') ){
			 	em_options_input_text ( sprintf(__( 'Single %s title format', 'events-manager'),__('event','events-manager')), 'dbem_event_page_title_format', sprintf(__( 'The format of a single %s page title.', 'events-manager'),__('event','events-manager')).' '.__( 'This is only used when showing events from other blogs.', 'events-manager').$events_placeholder_tip );
			}
			em_options_textarea ( sprintf(__('Single %s page format', 'events-manager'),__('event','events-manager')), 'dbem_single_event_format', sprintf(__( 'The format used to display %s content on single pages or elsewhere on your site.', 'events-manager'),__('event','events-manager')).$events_placeholder_tip );
			?>
			<tr class="em-header">
			    <td colspan="2">
			        <h4><?php echo sprintf(__('%s Excerpts','events-manager'),__('Event','events-manager')); ?></h4>
		 	        <em><?php echo sprintf(__('These formats can be used when WordPress automatically displays %s excerpts on your site and %s is enabled in your %s settings tab.','events-manager'),__('event','events-manager'),'<strong>'.__( 'Override Excerpts with Formats?', 'events-manager').'</strong>','<a href="#formats" class="nav-tab-link" rel="#em-menu-pages">'.__('Pages','events-manager').'  &gt; '.sprintf(__('%s List/Archives','events-manager'),__('Event','events-manager')).'</a>');?></em>
			    </td>
			</tr>
		 	<?php
		 	em_options_textarea ( sprintf(__('%s excerpt', 'events-manager'),__('Event','events-manager')), 'dbem_event_excerpt_format', __( 'Used if an excerpt has been defined.', 'events-manager').$events_placeholder_tip );				 	
		 	em_options_textarea ( sprintf(__('%s excerpt fallback', 'events-manager'),__('Event','events-manager')), 'dbem_event_excerpt_alt_format', __( 'Used if an excerpt has not been defined.', 'events-manager').$events_placeholder_tip );
			
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->

	<div  class="postbox " id="em-opt-search-form" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Search Form', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<table class="form-table em-search-form-main">
		    <tr class="em-header"><td colspan="2"><h4><?php _e('Main Search Fields','events-manager'); ?></h4></td></tr>
		    <tbody class="em-subsection">
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'Search', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show text search?', 'events-manager'), 'dbem_search_form_text', '', '', '#dbem_search_form_text_label_row' );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_text_label', __('Appears within the input box.','events-manager') );
			?>
			</tbody>
			<tbody class="em-settings-geocoding em-subsection">
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'Geolocation Search', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show geolocation search?', 'events-manager'), 'dbem_search_form_geo', '', '', '#dbem_search_form_geo_label_row, #dbem_search_form_geo_distance_default_row, #dbem_search_form_geo_unit_default_row' );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_geo_label', __('Appears within the input box.','events-manager') );
			em_options_input_text ( __( 'Default distance', 'events-manager'), 'dbem_search_form_geo_distance_default', __('Enter a number.','events-manager'), '');
			em_options_select ( __( 'Default distance unit', 'events-manager'), 'dbem_search_form_geo_unit_default', array('km'=>'km','mi'=>'mi'), '');
			?>
			</tbody>
		</table>
		<table class="form-table">
		    <tr class="em-header"><td colspan="2"><h4><?php _e('Advanced Search Fields','events-manager'); ?></h4></td></tr>
			<?php
			em_options_radio_binary ( __( 'Enable advanced fields?', 'events-manager'), 'dbem_search_form_advanced', __('Enables additional advanced search fields such as dates, country, etc.','events-manager'), '', '.em-search-form-advanced' );
			?>
			<tbody class="em-search-form-advanced">
			<?php 
			em_options_input_text ( __( 'Search button text', 'events-manager'), 'dbem_search_form_submit', __("If there's no fields to show in the main search section, this button will be used instead at the bottom of the advanced fields.",'events-manager'));
			em_options_radio_binary ( __( 'Hidden by default?', 'events-manager'), 'dbem_search_form_advanced_hidden', __('If set to yes, advanced search fields will be hidden by default and can be revealed by clicking the "Advanced Search" link.','events-manager'), '', '#dbem_search_form_advanced_show_row, #dbem_search_form_advanced_hide_row' );
			em_options_input_text ( __( 'Show label', 'events-manager'), 'dbem_search_form_advanced_show', __('Appears as the label for this search option.','events-manager') );
			em_options_input_text ( __( 'Hide label', 'events-manager'), 'dbem_search_form_advanced_hide', __('Appears as the label for this search option.','events-manager') );
			?>
			</tbody>
			<tbody class="em-search-form-advanced em-subsection">
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'Dates', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show date range?', 'events-manager'), 'dbem_search_form_dates', '', '', '#dbem_search_form_dates_label_row, #dbem_search_form_dates_separator_row' );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_dates_label', __('Appears as the label for this search option.','events-manager') );
			em_options_input_text ( __( 'Date Separator', 'events-manager'), 'dbem_search_form_dates_separator', sprintf(__( 'For when start/end %s are present, this will separate the two (include spaces here if necessary).', 'events-manager'), __('dates','events-manager')) );
			?>
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'Category', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show categories?', 'events-manager'), 'dbem_search_form_categories', '', '', '#dbem_search_form_category_label_row, #dbem_search_form_categories_label_row' );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_category_label', __('Appears as the label for this search option.','events-manager') );
			em_options_input_text ( __( 'Categories dropdown label', 'events-manager'), 'dbem_search_form_categories_label', __('Appears as the first default search option.','events-manager') );
			?>
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'Geolocation Search', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show distance options?', 'events-manager'), 'dbem_search_form_geo_units', '', '', '#dbem_search_form_geo_units_label_row, #dbem_search_form_geo_distance_options_row' );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_geo_units_label', __('Appears as the label for this search option.','events-manager') );
			em_options_input_text ( __( 'Distance Values', 'events-manager'), 'dbem_search_form_geo_distance_options', __('The numerical units shown to those searching by distance. Use comma-separated numbers, such as "25,50,100".','events-manager') );
			?>
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'Country', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show countries?', 'events-manager'), 'dbem_search_form_countries', '', '', '#dbem_search_form_country_label_row, #dbem_search_form_countries_label_row' );
			em_options_select ( __( 'Default Country', 'events-manager'), 'dbem_search_form_default_country', em_get_countries(__('no default country', 'events-manager')), __('Search form will be pre-selected with this country, if searching by country is disabled above, only search results from this country will be returned.','events-manager') );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_country_label', __('Appears as the label for this search option.','events-manager') );
			em_options_input_text ( __( 'All countries text', 'events-manager'), 'dbem_search_form_countries_label', __('Appears as the first default search option.','events-manager') );
			?>
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'Region', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show regions?', 'events-manager'), 'dbem_search_form_regions', '', '', '#dbem_search_form_region_label_row, #dbem_search_form_regions_label_row' );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_region_label', __('Appears as the label for this search option.','events-manager') );
			em_options_input_text ( __( 'All regions text', 'events-manager'), 'dbem_search_form_regions_label', __('Appears as the first default search option.','events-manager') );
			?>
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'State/County', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show states?', 'events-manager'), 'dbem_search_form_states', '', '', '#dbem_search_form_state_label_row, #dbem_search_form_states_label_row' );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_state_label', __('Appears as the label for this search option.','events-manager') );
			em_options_input_text ( __( 'All states text', 'events-manager'), 'dbem_search_form_states_label', __('Appears as the first default search option.','events-manager') );
			?>
			<tr class="em-subheader"><td colspan="2"><h5><?php esc_html_e( 'City/Town', 'events-manager'); ?></h5></td></tr>
			<?php
			em_options_radio_binary ( __( 'Show towns/cities?', 'events-manager'), 'dbem_search_form_towns', '', '', '#dbem_search_form_town_label_row, #dbem_search_form_towns_label_row' );
			em_options_input_text ( __( 'Label', 'events-manager'), 'dbem_search_form_town_label', __('Appears as the label for this search option.','events-manager') );
			em_options_input_text ( __( 'All towns/cities text', 'events-manager'), 'dbem_search_form_towns_label', __('Appears as the first default search option.','events-manager') );
			?>
			</tbody>
			<?php echo $save_button; ?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->

	<div  class="postbox " id="em-opt-date-time" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Date/Time', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<p class="em-boxheader"><?php
			$date_time_format_tip = sprintf(__('Date and Time formats follow the <a href="%s">WordPress time formatting conventions</a>', 'events-manager'), 'http://codex.wordpress.org/Formatting_Date_and_Time');
			echo $date_time_format_tip; 
		?></p>
		<table class="form-table">
    		<?php
			em_options_input_text ( __( 'Date Format', 'events-manager'), 'dbem_date_format', sprintf(__('For use with the %s placeholder','events-manager'),'<code>#_EVENTDATES</code>') );
			em_options_input_text ( __( 'Date Picker Format', 'events-manager'), 'dbem_date_format_js', sprintf(__( 'Same as <em>Date Format</em>, but this is used for the datepickers used by Events Manager. This uses a slightly different format to the others on here, for a list of characters to use, visit the <a href="%s">jQuery formatDate reference</a>', 'events-manager'),'http://docs.jquery.com/UI/Datepicker/formatDate') );
			em_options_input_text ( __( 'Date Separator', 'events-manager'), 'dbem_dates_separator', sprintf(__( 'For when start/end %s are present, this will separate the two (include spaces here if necessary).', 'events-manager'), __('dates','events-manager')) );
			em_options_input_text ( __( 'Time Format', 'events-manager'), 'dbem_time_format', sprintf(__('For use with the %s placeholder','events-manager'),'<code>#_EVENTTIMES</code>') );
			em_options_input_text ( __( 'Time Separator', 'events-manager'), 'dbem_times_separator', sprintf(__( 'For when start/end %s are present, this will separate the two (include spaces here if necessary).', 'events-manager'), __('times','events-manager')) );
			em_options_input_text ( __( 'All Day Message', 'events-manager'), 'dbem_event_all_day_message', sprintf(__( 'If an event lasts all day, this text will show if using the %s placeholder', 'events-manager'), '<code>#_EVENTTIMES</code>') );
			em_options_radio_binary ( __( 'Use 24h Format?', 'events-manager'), 'dbem_time_24h', __( 'When creating events, would you like your times to be shown in 24 hour format?', 'events-manager') );
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	      
   	<div  class="postbox " id="em-opt-calendar-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Calendar', 'events-manager'); ?></span></h3>
	<div class="inside">
    	<table class="form-table">
    		<?php
		    em_options_radio_binary ( __( 'Link directly to event on day with single event?', 'events-manager'), 'dbem_calendar_direct_links', __( "If a calendar day has only one event, you can force a direct link to the event (recommended to avoid duplicate content).",'events-manager') );
		    em_options_radio_binary ( __( 'Show list on day with single event?', 'events-manager'), 'dbem_display_calendar_day_single', __( "By default, if a calendar day only has one event, it display a single event when clicking on the link of that calendar date. If you select Yes here, you will get always see a list of events.",'events-manager') );
    		?>
    		<tr class="em-header"><td colspan="2"><h4><?php _e('Small Calendar','events-manager'); ?></h4></td></tr>
			<?php
		    em_options_input_text ( __( 'Month format', 'events-manager'), 'dbem_small_calendar_month_format', __('The format of the month/year header of the calendar.','events-manager').' '.$date_time_format_tip);
		    em_options_input_text ( __( 'Event titles', 'events-manager'), 'dbem_small_calendar_event_title_format', __( 'The format of the title, corresponding to the text that appears when hovering on an eventful calendar day.', 'events-manager').$events_placeholder_tip );
		    em_options_input_text ( __( 'Title separator', 'events-manager'), 'dbem_small_calendar_event_title_separator', __( 'The separator appearing on the above title when more than one events are taking place on the same day.', 'events-manager') );
		    em_options_radio_binary( __( 'Abbreviated weekdays', 'events-manager'), 'dbem_small_calendar_abbreviated_weekdays', __( 'The calendar headings uses abbreviated weekdays','events-manager') );
		    em_options_input_text ( __( 'Initial lengths', 'events-manager'), 'dbem_small_calendar_initials_length', __( 'Shorten the calendar headings containing the days of the week, use 0 for the full name.', 'events-manager').$events_placeholder_tip );
		    em_options_radio_binary( __( 'Show Long Events?', 'events-manager'), 'dbem_small_calendar_long_events', __( 'Events with multiple dates will appear on each of those dates in the calendar.','events-manager') );
		    ?>
    		<tr class="em-header"><td colspan="2"><h4><?php _e('Full Calendar','events-manager'); ?></h4></td></tr>
		    <?php
		    em_options_input_text ( __( 'Month format', 'events-manager'), 'dbem_full_calendar_month_format', __('The format of the month/year header of the calendar.','events-manager').' '.$date_time_format_tip);
		    em_options_input_text ( __( 'Event format', 'events-manager'), 'dbem_full_calendar_event_format', __( 'The format of each event when displayed in the full calendar. Remember to include <code>li</code> tags before and after the event.', 'events-manager').$events_placeholder_tip );
		    em_options_radio_binary( __( 'Abbreviated weekdays?', 'events-manager'), 'dbem_full_calendar_abbreviated_weekdays', __( 'Use abbreviations, e.g. Friday = Fri. Useful for certain languages where abbreviations differ from full names.','events-manager') );
		    em_options_input_text ( __( 'Initial lengths', 'events-manager'), 'dbem_full_calendar_initials_length', __( 'Shorten the calendar headings containing the days of the week, use 0 for the full name.', 'events-manager').$events_placeholder_tip);
		    em_options_radio_binary( __( 'Show Long Events?', 'events-manager'), 'dbem_full_calendar_long_events', __( 'Events with multiple dates will appear on each of those dates in the calendar.','events-manager') );
		    ?>		
		    <tr class="em-header"><td colspan="2"><h4><?php echo __('Calendar Day Event List Settings','events-manager'); ?></h4></td></tr>			
			<tr valign="top" id='dbem_display_calendar_orderby_row'>
		   		<th scope="row"><?php _e('Default event list ordering','events-manager'); ?></th>
		   		<td>   
					<select name="dbem_display_calendar_orderby" >
						<?php 
							$orderby_options = apply_filters('dbem_display_calendar_orderby_ddm', array(
								'event_name,event_start_time' => __('Order by event name, then event start time','events-manager'),
								'event_start_time,event_name' => __('Order by event start time, then event name','events-manager')
							)); 
						?>
						<?php foreach($orderby_options as $key => $value) : ?>   
		 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_display_calendar_orderby')) ? "selected='selected'" : ''; ?>>
		 					<?php echo esc_html($value) ?>
		 				</option>
						<?php endforeach; ?>
					</select> 
					<select name="dbem_display_calendar_order" >
						<?php 
						$ascending = __('Ascending','events-manager');
						$descending = __('Descending','events-manager');
						$order_options = apply_filters('dbem_display_calendar_order_ddm', array(
							'ASC' => __('All Ascending','events-manager'),
							'DESC,ASC' => "$descending, $ascending",
							'DESC,DESC' => "$descending, $descending",
							'DESC' => __('All Descending','events-manager')
						)); 
						?>
						<?php foreach( $order_options as $key => $value) : ?>   
		 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_display_calendar_order')) ? "selected='selected'" : ''; ?>>
		 					<?php echo esc_html($value) ?>
		 				</option>
						<?php endforeach; ?>
					</select>
					<br/>
					<em><?php _e('When Events Manager displays lists of events the default behavior is ordering by start date in ascending order. To change this, modify the values above.','events-manager'); ?></em>
				</td>
		   	</tr>
		   	<?php 
		   		em_options_input_text ( __( 'Calendar events/day limit', 'events-manager'), 'dbem_display_calendar_events_limit', __( 'Limits the number of events on each calendar day. Leave blank for no limit.', 'events-manager') );
		   		em_options_input_text ( __( 'More Events message', 'events-manager'), 'dbem_display_calendar_events_limit_msg', __( 'Text with link to calendar day page with all events for that day if there are more events than the limit above, leave blank for no link as the day number is also a link.', 'events-manager') );
		   	?>
		    <tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('iCal Feed Settings','events-manager'),__('Event','events-manager')); ?></h4></td></tr>
		    <?php 
			em_options_input_text ( __( 'iCal Title', 'events-manager'), 'dbem_ical_description_format', __( 'The title that will appear in the calendar.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'iCal Description', 'events-manager'), 'dbem_ical_real_description_format', __( 'The description of the event that will appear in the calendar.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'iCal Location', 'events-manager'), 'dbem_ical_location_format', __( 'The location information that will appear in the calendar.', 'events-manager').$events_placeholder_tip );
			em_options_select( __('iCal Scope','events-manager'), 'dbem_ical_scope', em_get_scopes(), __('Choose to show events within a specific time range.','events-manager'));
			em_options_input_text ( __( 'iCal Limit', 'events-manager'), 'dbem_ical_limit', __( 'Limits the number of future events shown (0 = unlimited).', 'events-manager') );						
		    echo $save_button;        
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	
	<?php if( get_option('dbem_locations_enabled') ): ?>
	<div  class="postbox " id="em-opt-locations-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Locations', 'events-manager'); ?> </span></h3>
	<div class="inside">
    	<table class="form-table">
		 	<tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('%s Page','events-manager'),__('Locations','events-manager')); ?></h4></td></tr>
			<?php
			em_options_textarea ( sprintf(__('%s list header format','events-manager'),__('Locations','events-manager')), 'dbem_location_list_item_format_header', sprintf(__( 'This content will appear just above your code for the %s list format below. Default is blank', 'events-manager'), __('locations','events-manager')) );
		 	em_options_textarea ( sprintf(__('%s list item format','events-manager'),__('Locations','events-manager')), 'dbem_location_list_item_format', sprintf(__( 'The format of a single %s in a list.', 'events-manager'), __('locations','events-manager')).$locations_placeholder_tip );
			em_options_textarea ( sprintf(__('%s list footer format','events-manager'),__('Locations','events-manager')), 'dbem_location_list_item_format_footer', sprintf(__( 'This content will appear just below your code for the %s list format above. Default is blank', 'events-manager'), __('locations','events-manager')) );
			em_options_input_text ( sprintf(__( 'No %s message', 'events-manager'),__('Locations','events-manager')), 'dbem_no_locations_message', sprintf( __( 'The message displayed when no %s are available.', 'events-manager'), __('locations','events-manager')) );
		 	?>
		 	<tr class="em-header">
		 	    <td colspan="2">
		 	        <h4><?php echo sprintf(__('Single %s Page','events-manager'),__('Location','events-manager')); ?></h4>
		 	        <em><?php echo sprintf(__('These formats can be used on %s pages or on other areas of your site displaying an %s.','events-manager'),__('location','events-manager'),__('location','events-manager'));?></em>
		 	</tr>
		 	<?php
			if( EM_MS_GLOBAL && get_option('dbem_ms_global_location_links') ){
			  em_options_input_text (sprintf( __( 'Single %s title format', 'events-manager'),__('location','events-manager')), 'dbem_location_page_title_format', sprintf(__( 'The format of a single %s page title.', 'events-manager'),__('location','events-manager')).$locations_placeholder_tip );
			}
			em_options_textarea ( sprintf(__('Single %s page format', 'events-manager'),__('location','events-manager')), 'dbem_single_location_format', sprintf(__( 'The format of a single %s page.', 'events-manager'),__('location','events-manager')).$locations_placeholder_tip );
			?>
			<tr class="em-header">
			    <td colspan="2">
			        <h4><?php echo sprintf(__('%s Excerpts','events-manager'),__('Location','events-manager')); ?></h4>
		 	        <em><?php echo sprintf(__('These formats can be used when WordPress automatically displays %s excerpts on your site and %s is enabled in your %s settings tab.','events-manager'),__('location','events-manager'),'<strong>'.__( 'Override Excerpts with Formats?', 'events-manager').'</strong>','<a href="#formats" class="nav-tab-link" rel="#em-menu-pages">'.__('Pages','events-manager').'  &gt; '.sprintf(__('%s List/Archives','events-manager'),__('Location','events-manager')).'</a>');?></em>
			    </td>
			</tr>
		 	<?php
		 	em_options_textarea ( sprintf(__('%s excerpt', 'events-manager'),__('Location','events-manager')), 'dbem_location_excerpt_format', __( 'Used if an excerpt has been defined.', 'events-manager').$locations_placeholder_tip );				 	
		 	em_options_textarea ( sprintf(__('%s excerpt fallback', 'events-manager'),__('Location','events-manager')), 'dbem_location_excerpt_alt_format', __( 'Used if an excerpt has not been defined.', 'events-manager').$locations_placeholder_tip );
			?>
		 	<tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('%s List Formats','events-manager'),__('Event','events-manager')); ?></h4></td></tr>
		 	<?php
		 	em_options_input_text ( __( 'Default event list format header', 'events-manager'), 'dbem_location_event_list_item_header_format', __( 'This content will appear just above your code for the default event list format. Default is blank', 'events-manager') );
		 	em_options_textarea ( sprintf(__( 'Default %s list format', 'events-manager'),__('events','events-manager')), 'dbem_location_event_list_item_format', sprintf(__( 'The format of the events the list inserted in the location page through the %s element.', 'events-manager').$events_placeholder_tip, '<code>#_LOCATIONNEXTEVENTS</code>, <code>#_LOCATIONPASTEVENTS</code>, <code>#_LOCATIONALLEVENTS</code>') );
			em_options_input_text ( __( 'Default event list format footer', 'events-manager'), 'dbem_location_event_list_item_footer_format', __( 'This content will appear just below your code for the default event list format. Default is blank', 'events-manager') );
			em_options_textarea ( sprintf(__( 'No %s message', 'events-manager'),__('events','events-manager')), 'dbem_location_no_events_message', sprintf(__( 'The message to be displayed in the list generated by %s when no events are available.', 'events-manager'), '<code>#_LOCATIONNEXTEVENTS</code>, <code>#_LOCATIONPASTEVENTS</code>, <code>#_LOCATIONALLEVENTS</code>') );
			?>
		 	<tr class="em-header"><td colspan="2">
		 		<h4><?php echo sprintf(__('Single %s Format','events-manager'),__('Event','events-manager')); ?></h4>
		 		<p><?php echo sprintf(__('The settings below are used when using the %s placeholder','events-manager'), '<code>#_LOCATIONNEXTEVENT</code>'); ?></p>
		 	</td></tr>
		 	<?php
		 	em_options_input_text ( __( 'Next event format', 'events-manager'), 'dbem_location_event_single_format', sprintf(__( 'The format of the next upcoming event in this %s.', 'events-manager'),__('location','events-manager')).$events_placeholder_tip );
		 	em_options_input_text ( sprintf(__( 'No %s message', 'events-manager'),__('events','events-manager')), 'dbem_location_no_event_message', sprintf(__( 'The message to be displayed in the list generated by %s when no events are available.', 'events-manager'), '<code>#_LOCATIONNEXTEVENT</code>') );
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php endif; ?>
	
	<?php if( get_option('dbem_categories_enabled') && !(EM_MS_GLOBAL && !is_main_site()) ): ?>
	<div  class="postbox " id="em-opt-categories-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Event Categories', 'events-manager'); ?> </span></h3>
	<div class="inside">
    	<table class="form-table">
    		<?php
    		em_options_input_text(sprintf(esc_html__('Default %s color','events-manager'), esc_html__('category','events-manager')), 'dbem_category_default_color', sprintf(esc_html_x('Colors must be in a valid %s format, such as #FF00EE.', 'hex format', 'events-manager'), '<a href="http://en.wikipedia.org/wiki/Web_colors">hex</a>'));
    		?>
		 	<tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('%s Page','events-manager'),__('Categories','events-manager')); ?></h4></td></tr>
			<?php
			em_options_textarea ( sprintf(__('%s list header format','events-manager'),__('Categories','events-manager')), 'dbem_categories_list_item_format_header', sprintf(__( 'This content will appear just above your code for the %s list format below. Default is blank', 'events-manager'), __('categories','events-manager')) );
		 	em_options_textarea ( sprintf(__('%s list item format','events-manager'),__('Categories','events-manager')), 'dbem_categories_list_item_format', sprintf(__( 'The format of a single %s in a list.', 'events-manager'), __('categories','events-manager')).$categories_placeholder_tip );
			em_options_textarea ( sprintf(__('%s list footer format','events-manager'),__('Categories','events-manager')), 'dbem_categories_list_item_format_footer', sprintf(__( 'This content will appear just below your code for the %s list format above. Default is blank', 'events-manager'), __('categories','events-manager')) );
			em_options_input_text ( sprintf(__( 'No %s message', 'events-manager'),__('Categories','events-manager')), 'dbem_no_categories_message', sprintf( __( 'The message displayed when no %s are available.', 'events-manager'), __('categories','events-manager')) );
		 	?>
		 	<tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('Single %s Page','events-manager'),__('Category','events-manager')); ?></h4></td></tr>
		 	<?php
			em_options_input_text ( sprintf(__( 'Single %s title format', 'events-manager'),__('category','events-manager')), 'dbem_category_page_title_format', __( 'The format of a single category page title.', 'events-manager').$categories_placeholder_tip );
			em_options_textarea ( sprintf(__('Single %s page format', 'events-manager'),__('category','events-manager')), 'dbem_category_page_format', sprintf(__( 'The format of a single %s page.', 'events-manager'),__('category','events-manager')).$categories_placeholder_tip );
		 	?>
		 	<tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('%s List Formats','events-manager'),__('Event','events-manager')); ?></h4></td></tr>
		 	<?php
		 	em_options_input_text ( __( 'Default event list format header', 'events-manager'), 'dbem_category_event_list_item_header_format', __( 'This content will appear just above your code for the default event list format. Default is blank', 'events-manager') );
		 	em_options_textarea ( sprintf(__( 'Default %s list format', 'events-manager'),__('events','events-manager')), 'dbem_category_event_list_item_format', sprintf(__( 'The format of the events the list inserted in the category page through the %s element.', 'events-manager').$events_placeholder_tip, '<code>#_CATEGORYPASTEVENTS</code>, <code>#_CATEGORYNEXTEVENTS</code>, <code>#_CATEGORYALLEVENTS</code>') );
			em_options_input_text ( __( 'Default event list format footer', 'events-manager'), 'dbem_category_event_list_item_footer_format', __( 'This content will appear just below your code for the default event list format. Default is blank', 'events-manager') );
			em_options_textarea ( sprintf(__( 'No %s message', 'events-manager'),__('events','events-manager')), 'dbem_category_no_events_message', sprintf(__( 'The message to be displayed in the list generated by %s when no events are available.', 'events-manager'), '<code>#_CATEGORYPASTEVENTS</code>, <code>#_CATEGORYNEXTEVENTS</code>, <code>#_CATEGORYALLEVENTS</code>') );
			?>
		 	<tr class="em-header"><td colspan="2">
		 		<h4><?php echo sprintf(__('Single %s Format','events-manager'),__('Event','events-manager')); ?></h4>
		 		<p><?php echo sprintf(__('The settings below are used when using the %s placeholder','events-manager'), '<code>#_CATEGORYNEXTEVENT</code>'); ?></p>
		 	</td></tr>
		 	<?php
		 	em_options_input_text ( __( 'Next event format', 'events-manager'), 'dbem_category_event_single_format', sprintf(__( 'The format of the next upcoming event in this %s.', 'events-manager'),__('category','events-manager')).$events_placeholder_tip );
		 	em_options_input_text ( sprintf(__( 'No %s message', 'events-manager'),__('events','events-manager')), 'dbem_category_no_event_message', sprintf(__( 'The message to be displayed in the list generated by %s when no events are available.', 'events-manager'), '<code>#_CATEGORYNEXTEVENT</code>') );
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php endif; ?>
	
	<?php if( get_option('dbem_tags_enabled') ): ?>
	<div  class="postbox " id="em-opt-tags-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Event Tags', 'events-manager'); ?> </span></h3>
	<div class="inside">
    	<table class="form-table">
		 	<tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('%s Page','events-manager'),__('Tags','events-manager')); ?></h4></td></tr>
			<?php
			em_options_textarea ( sprintf(__('%s list header format','events-manager'),__('Tags','events-manager')), 'dbem_tags_list_item_format_header', sprintf(__( 'This content will appear just above your code for the %s list format below. Default is blank', 'events-manager'), __('tags','events-manager')) );
		 	em_options_textarea ( sprintf(__('%s list item format','events-manager'),__('Tags','events-manager')), 'dbem_tags_list_item_format', sprintf(__( 'The format of a single %s in a list.', 'events-manager'), __('tags','events-manager')).$categories_placeholder_tip );
			em_options_textarea ( sprintf(__('%s list footer format','events-manager'),__('Tags','events-manager')), 'dbem_tags_list_item_format_footer', sprintf(__( 'This content will appear just below your code for the %s list format above. Default is blank', 'events-manager'), __('tags','events-manager')) );
			em_options_input_text ( sprintf(__( 'No %s message', 'events-manager'),__('Tags','events-manager')), 'dbem_no_tags_message', sprintf( __( 'The message displayed when no %s are available.', 'events-manager'), __('tags','events-manager')) );
		 	?>
		 	<tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('Single %s Page','events-manager'),__('Tag','events-manager')); ?></h4></td></tr>
		 	<?php
			em_options_input_text ( sprintf(__( 'Single %s title format', 'events-manager'),__('tag','events-manager')), 'dbem_tag_page_title_format', __( 'The format of a single tag page title.', 'events-manager').$categories_placeholder_tip );
			em_options_textarea ( sprintf(__('Single %s page format', 'events-manager'),__('tag','events-manager')), 'dbem_tag_page_format', sprintf(__( 'The format of a single %s page.', 'events-manager'),__('tag','events-manager')).$categories_placeholder_tip );
		 	?>
		 	<tr class="em-header"><td colspan="2"><h4><?php echo sprintf(__('%s List Formats','events-manager'),__('Event','events-manager')); ?></h4></td></tr>
		 	<?php
			em_options_input_text ( __( 'Default event list format header', 'events-manager'), 'dbem_tag_event_list_item_header_format', __( 'This content will appear just above your code for the default event list format. Default is blank', 'events-manager') );
		 	em_options_textarea ( sprintf(__( 'Default %s list format', 'events-manager'),__('events','events-manager')), 'dbem_tag_event_list_item_format', __( 'The format of the events the list inserted in the tag page through the <code>#_TAGNEXTEVENTS</code>, <code>#_TAGNEXTEVENTS</code> and <code>#_TAGALLEVENTS</code> element.', 'events-manager').$categories_placeholder_tip );
			em_options_input_text ( __( 'Default event list format footer', 'events-manager'), 'dbem_tag_event_list_item_footer_format', __( 'This content will appear just below your code for the default event list format. Default is blank', 'events-manager') );
			em_options_textarea ( sprintf(__( 'No %s message', 'events-manager'),__('events','events-manager')), 'dbem_tag_no_events_message', __( 'The message to be displayed in the list generated by <code>#_TAGNEXTEVENTS</code>, <code>#_TAGNEXTEVENTS</code> and <code>#_TAGALLEVENTS</code> when no events are available.', 'events-manager') );
			?>
		 	<tr class="em-header"><td colspan="2">
		 		<h4><?php echo sprintf(__('Single %s Format','events-manager'),__('Event','events-manager')); ?></h4>
		 		<p><?php echo sprintf(__('The settings below are used when using the %s placeholder','events-manager'), '<code>#_TAGNEXTEVENT</code>'); ?></p>
		 	</td></tr>
		 	<?php
		 	em_options_input_text ( __( 'Next event format', 'events-manager'), 'dbem_tag_event_single_format', sprintf(__( 'The format of the next upcoming event in this %s.', 'events-manager'),__('tag','events-manager')).$events_placeholder_tip );
		 	em_options_input_text ( sprintf(__( 'No %s message', 'events-manager'),__('events','events-manager')), 'dbem_tag_no_event_message', sprintf(__( 'The message to be displayed in the list generated by %s when no events are available.', 'events-manager'), '<code>#_CATEGORYNEXTEVENT</code>') );
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	<?php endif; ?>
	
	<div  class="postbox " id="em-opt-rss-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'RSS', 'events-manager'); ?> </span></h3>
	<div class="inside">
    	<table class="form-table">
			<?php				
			em_options_input_text ( __( 'RSS main title', 'events-manager'), 'dbem_rss_main_title', __( 'The main title of your RSS events feed.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'RSS main description', 'events-manager'), 'dbem_rss_main_description', __( 'The main description of your RSS events feed.', 'events-manager') );
			em_options_input_text ( __( 'RSS title format', 'events-manager'), 'dbem_rss_title_format', __( 'The format of the title of each item in the events RSS feed.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'RSS description format', 'events-manager'), 'dbem_rss_description_format', __( 'The format of the description of each item in the events RSS feed.', 'events-manager').$events_placeholder_tip );
			em_options_input_text ( __( 'RSS limit', 'events-manager'), 'dbem_rss_limit', __( 'Limits the number of future events shown (0 = unlimited).', 'events-manager') );
			em_options_select( __('RSS Scope','events-manager'), 'dbem_rss_scope', em_get_scopes(), __('Choose to show events within a specific time range.','events-manager'));
			?>							
			<tr valign="top" id='dbem_rss_orderby_row'>
		   		<th scope="row"><?php _e('Default event list ordering','events-manager'); ?></th>
		   		<td>   
					<select name="dbem_rss_orderby" >
						<?php 
							$orderby_options = apply_filters('em_settings_events_default_orderby_ddm', array(
								'event_start_date,event_start_time,event_name' => __('Order by start date, start time, then event name','events-manager'),
								'event_name,event_start_date,event_start_time' => __('Order by name, start date, then start time','events-manager'),
								'event_name,event_end_date,event_end_time' => __('Order by name, end date, then end time','events-manager'),
								'event_end_date,event_end_time,event_name' => __('Order by end date, end time, then event name','events-manager'),
							)); 
						?>
						<?php foreach($orderby_options as $key => $value) : ?>   
		 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_rss_orderby')) ? "selected='selected'" : ''; ?>>
		 					<?php echo esc_html($value); ?>
		 				</option>
						<?php endforeach; ?>
					</select> 
					<select name="dbem_rss_order" >
						<?php 
						$ascending = __('Ascending','events-manager');
						$descending = __('Descending','events-manager');
						$order_options = apply_filters('em_settings_events_default_order_ddm', array(
							'ASC' => __('All Ascending','events-manager'),
							'DESC,ASC,ASC' => __("$descending, $ascending, $ascending",'events-manager'),
							'DESC,DESC,ASC' => __("$descending, $descending, $ascending",'events-manager'),
							'DESC' => __('All Descending','events-manager'),
							'ASC,DESC,ASC' => __("$ascending, $descending, $ascending",'events-manager'),
							'ASC,DESC,DESC' => __("$ascending, $descending, $descending",'events-manager'),
							'ASC,ASC,DESC' => __("$ascending, $ascending, $descending",'events-manager'),
							'DESC,ASC,DESC' => __("$descending, $ascending, $descending",'events-manager'),
						)); 
						?>
						<?php foreach( $order_options as $key => $value) : ?>   
		 				<option value='<?php echo esc_attr($key) ?>' <?php echo ($key == get_option('dbem_rss_order')) ? "selected='selected'" : ''; ?>>
		 					<?php echo esc_html($value); ?>
		 				</option>
						<?php endforeach; ?>
					</select>
					<br/>
					<em><?php _e('When Events Manager displays lists of events the default behavior is ordering by start date in ascending order. To change this, modify the values above.','events-manager'); ?></em>
				</td>
		   	</tr>
			<?php
			echo $save_button;
			?>
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	
	<div  class="postbox " id="em-opt-maps-formats" >
	<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3><span><?php _e ( 'Maps', 'events-manager'); ?> </span></h3>
	<div class="inside">
		<p class="em-boxheader"><?php echo sprintf(__('You can use Google Maps to show where your events are located. For more information on using maps, <a href="%s">see our documentation</a>.','events-manager'),'http://wp-events-plugin.com/documentation/google-maps/'); ?>
		<table class='form-table'> 
			<?php $gmap_is_active = get_option ( 'dbem_gmap_is_active' ); ?>
			<tr valign="top">
				<th scope="row"><?php _e ( 'Enable Google Maps integration?', 'events-manager'); ?></th>
				<td>
					<?php _e ( 'Yes' ); ?> <input id="dbem_gmap_is_active_yes" name="dbem_gmap_is_active" type="radio" value="1" <?php echo ($gmap_is_active) ? "checked='checked'":''; ?> />
					<?php _e ( 'No' ); ?> <input name="dbem_gmap_is_active" type="radio" value="0" <?php echo ($gmap_is_active) ? '':"checked='checked'"; ?> /><br />
					<em><?php _e ( 'Check this option to enable Goggle Map integration.', 'events-manager')?></em>
				</td>
				<?php em_options_input_text(__('Default map width','events-manager'), 'dbem_map_default_width', sprintf(__('Can be in form of pixels or a percentage such as %s or %s.', 'events-manager'), '<code>100%</code>', '<code>100px</code>')); ?>
				<?php em_options_input_text(__('Default map height','events-manager'), 'dbem_map_default_height', sprintf(__('Can be in form of pixels or a percentage such as %s or %s.', 'events-manager'), '<code>100%</code>', '<code>100px</code>')); ?>
			</tr>
			<tr class="em-header"><td colspan="2">
				<h4><?php _e('Global Map Format','events-manager'); ?></h4>
				<p><?php echo sprintf(__('If you use the %s <a href="%s">shortcode</a>, you can display a map of all your locations and events, the settings below will be used.','events-manager'), '<code>[locations_map]</code>','http://wp-events-plugin.com/documentation/shortcodes/'); ?></p>
			</td></tr>
			<?php
			em_options_textarea ( __( 'Location balloon format', 'events-manager'), 'dbem_map_text_format', __( 'The format of the text appearing in the balloon describing the location.', 'events-manager').' '.__( 'Event.', 'events-manager').$locations_placeholder_tip );
			?>
			<tr class="em-header"><td colspan="2">
				<h4><?php _e('Single Location/Event Map Format','events-manager'); ?></h4>
				<p><?php echo sprintf(_e('If you use the <code>#_LOCATIONMAP</code> <a href="%s">placeholder</a> when displaying individual event and location information, the settings below will be used.','events-manager'), '<code>[locations_map]</code>','http://wp-events-plugin.com/documentation/placeholders/'); ?></p>
			</td></tr>
			<?php
			em_options_textarea ( __( 'Location balloon format', 'events-manager'), 'dbem_location_baloon_format', __( 'The format of the text appearing in the balloon describing the location.', 'events-manager').$events_placeholder_tip );
			echo $save_button;     
			?> 
		</table>
	</div> <!-- . inside -->
	</div> <!-- .postbox -->
	
	<?php do_action('em_options_page_footer_formats'); ?>
	
</div> <!-- .em-menu-formats -->