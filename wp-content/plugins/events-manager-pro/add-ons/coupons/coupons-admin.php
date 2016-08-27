<?php
class EM_Coupons_Admin {
    static function init(){
		include('coupon-admin.php');
        //coupon admin add/edit page
        add_action('em_create_events_submenu', 'EM_Coupons_Admin::admin_menu',10,1);
    }
    
    /**
     * @param EM_Coupon $EM_Coupon
     */
    static function count_sync( $EM_Coupon ){
    	global $wpdb;
        //a bit hacky, but this is the only way at least for now
        $coupon_search = str_replace('a:1:{', '', serialize(array('coupon_code'=>$EM_Coupon->coupon_code)));
        $coupon_search = substr($coupon_search, 0, strlen($coupon_search)-1 );
        $coupons_count = $wpdb->get_var('SELECT COUNT(*) FROM '.EM_BOOKINGS_TABLE." WHERE booking_meta LIKE '%{$coupon_search}%'");
        if( $EM_Coupon->get_count() != $coupons_count ){
			global $wpdb;
            $wpdb->update(EM_META_TABLE, array('meta_value'=>$coupons_count), array('object_id'=>$EM_Coupon->coupon_id, 'meta_key'=>'coupon-count'));
            return true;
        }
        return false;
    }
	
	/**
	 * @param EM_Event $EM_Event
	 */
	static function admin_meta_box($EM_Event){
		//Get available coupons for user
		global $wpdb;
		$coupons = array();
		//get event owner to search for
		$owner = empty($EM_Event->event_owner) ? get_current_user_id() : $EM_Event->event_owner;
		?>
		<h4><?php _e('Coupons','em-pro'); ?> <span>[<a href="#" id="em-event-bookings-coupons-trigger"><?php _e('show coupons', 'em-pro'); ?></a>]</span></h4>
		<script type="text/javascript">
		   jQuery(document).ready(function($){ 
			   $('#em-event-bookings-coupons-trigger').click( function(e){
				   e.preventDefault();
				   el = $(this);
				    if( el.text() == '<?php _e('show coupons', 'em-pro'); ?>' ){
				    	el.text('<?php _e('hide coupons', 'em-pro'); ?>');
					    $('#em-event-bookings-coupons').show();
				    }else{
				    	el.text('<?php _e('show coupons', 'em-pro'); ?>');
					    $('#em-event-bookings-coupons').hide();
				    }
			   }); 
		   });
		</script>
    	<div id="em-event-bookings-coupons" style="display:none;">
		<?php
		//show coupons that aren't event-wide or site-wide, if not in MB mode
		if( !get_option('dbem_multiple_bookings') && current_user_can(EM_Coupons::$can_manage) ){ 
        //not in multiple bookings mode and can create their own coupons 
        ?>
    		<p><em><?php _e('Coupons selected here will be applied to bookings made for this event.','em-pro'); ?></em></p>
    		<?php
    		//get event owner's coupons
    		$coupons = EM_Coupons::get(array('owner'=>$owner, 'sitewide'=>0, 'eventwide'=>0 ));
    		//loop through coupons and output checkboxes, or let user know no coupons were created.
    		if(count($coupons) > 0): foreach($coupons as $EM_Coupon): /* @var $EM_Coupon EM_Coupon */ ?>  
    			<label>
    				<input type="checkbox" name="em_coupons[]" value="<?php echo $EM_Coupon->coupon_id; ?>" <?php if(in_array($EM_Coupon->coupon_id, EM_Coupons::event_get_coupon_ids($EM_Event))) echo 'checked="checked"'; ?>/>
    				<strong><?php echo $EM_Coupon->coupon_code; ?></strong> 
    				(<em><?php echo esc_html($EM_Coupon->coupon_name); ?></em>) - <?php echo $EM_Coupon->get_discount_text(); ?>
    			</label><br />
    		<?php endforeach; else: ?>
    			<?php _e('No coupons created yet.','em-pro'); ?>
    		<?php endif; ?>
		<?php 
		}
    	//Show all coupons that are automatically applied, i.e. sitewide and eventwide 
    	$coupons = EM_Coupons::get( array('owner'=>$owner, 'sitewide'=>1, 'eventwide'=>1) );
        if( count($coupons) > 0 ): ?>
			<p><em><?php _e('The following codes will be automatically available to this event.','em-pro')?></em></p> 
			<?php foreach($coupons as $EM_Coupon): /* @var $EM_Coupon EM_Coupon */ ?>
				<p style="margin:0px 0px 5px 0px">
					<?php echo '<strong>'.esc_html($EM_Coupon->coupon_code).'</strong> - '. esc_html($EM_Coupon->get_discount_text()); ?><br />
					<em><?php echo esc_html($EM_Coupon->coupon_name); if(!empty($EM_Coupon->coupon_description)) echo esc_html(' - '. $EM_Coupon->coupon_description); ?></em>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
		<?php
	}
    
    static function admin_menu($plugin_pages){
    	$plugin_pages[] = add_submenu_page('edit.php?post_type='.EM_POST_TYPE_EVENT, __('Coupons','em-pro'),__('Coupons Manager','em-pro'),'manage_others_bookings','events-manager-coupons','EM_Coupons_Admin::admin_page');
    	return $plugin_pages; //use wp action/filters to mess with the menus
    }
    
    static function admin_page($args = array()){
    	global $EM_Coupon, $EM_Notices;
    	//load coupon if necessary
    	$EM_Coupon = !empty($_REQUEST['coupon_id']) ? new EM_Coupon_Admin($_REQUEST['coupon_id']) : new EM_Coupon_Admin();
    	//save coupon if necessary
    	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'coupon_save' && wp_verify_nonce($_REQUEST['_wpnonce'], 'coupon_save') ){
    		if ( $EM_Coupon->get_post() && $EM_Coupon->save() ) {
    			//Success notice
    			$EM_Notices->add_confirm( $EM_Coupon->feedback_message );
    		}else{
    			$EM_Notices->add_error( $EM_Coupon->get_errors() );
    		}
    	}
    	//Delete if necessary
    	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'coupon_delete' && wp_verify_nonce($_REQUEST['_wpnonce'], 'coupon_delete_'.$EM_Coupon->coupon_id) ){
    		if ( $EM_Coupon->delete() ) {
    			$EM_Notices->add_confirm( $EM_Coupon->feedback_message );
    		}else{
    			$EM_Notices->add_error( $EM_Coupon->get_errors() );
    		}
    	}
    	//Display relevant page
    	if( !empty($_GET['action']) && $_GET['action']=='edit' ){
    		if( empty($_REQUEST['redirect_to']) ){
    			$_REQUEST['redirect_to'] = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>null, 'coupon_id'=>null));
    		}
    		self::edit_form();
    	}elseif( !empty($_GET['action']) && $_GET['action']=='view' ){
    		self::view_page();
    	}else{
    		self::select_page();
    	}
    }
    
    static function select_page() {
    	global $wpdb, $EM_Pro, $EM_Notices;
    	$url = empty($url) ? $_SERVER['REQUEST_URI']:$url; //url to this page
    	$limit = ( !empty($_REQUEST['limit']) && is_numeric($_REQUEST[ 'limit']) ) ? $_REQUEST['limit'] : 20;//Default limit
    	$page = ( !empty($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
    	$offset = ( $page > 1 ) ? ($page-1)*$limit : 0;
    	$args = array('limit'=>$limit, 'offset'=>$offset);
    	$coupons_mine_count = EM_Coupons::count( array('owner'=>get_current_user_id()) );
    	$coupons_all_count = current_user_can('manage_others_bookings') ? EM_Coupons::count():0;
    	if( !empty($_REQUEST['view']) && $_REQUEST['view'] == 'others' && current_user_can('manage_others_bookings') ){
    		$coupons = EM_Coupons::get( $args );
    		$coupons_count = $coupons_all_count;
    	}else{
    		$coupons = EM_Coupons::get( array_merge($args, array('owner'=>get_current_user_id())) );
    		$coupons_count = $coupons_mine_count;
    	}
    	?>
		<div class='wrap'>
			<div class="icon32" id="icon-bookings"><br></div>
			<h2><?php _e('Edit Coupons','em-pro'); ?>
				<a href="<?php echo add_query_arg(array('action'=>'edit')); ?>" class="add-new-h2"><?php _e('Add New','dbem'); ?></a>
			</h2>
			<?php echo $EM_Notices; ?>
			<form id='coupons-filter' method='post' action=''>
				<input type='hidden' name='pno' value='<?php echo $page ?>' />
				<div class="tablenav">			
					<div class="alignleft actions">
						<div class="subsubsub">
							<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], array('view'=>null, 'pno'=>null)); ?>' <?php echo ( empty($_REQUEST['view']) ) ? 'class="current"':''; ?>><?php echo sprintf( __( 'My %s', 'dbem' ), __('Coupons','em-pro')); ?> <span class="count">(<?php echo $coupons_mine_count; ?>)</span></a>
							<?php if( current_user_can('manage_others_bookings') ): ?>
							&nbsp;|&nbsp;
							<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], array('view'=>'others', 'pno'=>null)); ?>' <?php echo ( !empty($_REQUEST['view']) && $_REQUEST['view'] == 'others' ) ? 'class="current"':''; ?>><?php echo sprintf( __( 'All %s', 'dbem' ), __('Coupons','em-pro')); ?> <span class="count">(<?php echo $coupons_all_count; ?>)</span></a>
							<?php endif; ?>
						</div>
					</div>
					<?php
					if ( $coupons_count >= $limit ) {
						$coupons_nav = em_admin_paginate( $coupons_count, $limit, $page );
						echo $coupons_nav;
					}
					?>
				</div>
				<?php if ( $coupons_count > 0 ) : ?>
				<table class='widefat'>
					<thead>
						<tr>
							<th><?php _e('Name', 'em-pro') ?></th>
							<th><?php _e('Code', 'em-pro') ?></th>
							<th><?php _e('Created By', 'em-pro') ?></th>
							<th><?php _e('Description', 'em-pro') ?></th>  
							<th><?php _e('Discount', 'em-pro') ?></th>   
							<th><?php _e('Uses', 'em-pro') ?></th>       
						</tr> 
					</thead>
					<tfoot>
						<tr>
							<th><?php _e('Name', 'em-pro') ?></th>
							<th><?php _e('Code', 'em-pro') ?></th>
							<th><?php _e('Created By', 'em-pro') ?></th>
							<th><?php _e('Description', 'em-pro') ?></th>  
							<th><?php _e('Discount', 'em-pro') ?></th>   
							<th><?php _e('Uses', 'em-pro') ?></th>
						</tr>             
					</tfoot>
					<tbody>
						<?php foreach ($coupons as $EM_Coupon) : ?>	
							<tr>
								<td>
									<a href='<?php echo admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&amp;page=events-manager-coupons&amp;action=edit&amp;coupon_id='.$EM_Coupon->coupon_id); ?>'><?php echo $EM_Coupon->coupon_name ?></a>
									<div class="row-actions">
										<span class="trash"><a class="submitdelete" href="<?php echo add_query_arg(array('coupon_id'=>$EM_Coupon->coupon_id,'action'=>'coupon_delete','_wpnonce'=>wp_create_nonce('coupon_delete_'.$EM_Coupon->coupon_id))) ?>"><?php _e('Delete','em-pro')?></a></span>
									</div>
								</td>
								<td><?php echo esc_html($EM_Coupon->coupon_code); ?></td>
								<td><a href="<?php echo admin_url('user-edit.php?user_id='.$EM_Coupon->get_person()->ID); ?>"><?php echo $EM_Coupon->get_person()->get_name(); ?></a></td>
								<td><?php echo esc_html($EM_Coupon->coupon_description); ?></td>  
								<td><?php echo $EM_Coupon->get_discount_text(); ?></td>            
								<td>
									<a href='<?php echo admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&amp;page=events-manager-coupons&amp;action=view&amp;coupon_id='.$EM_Coupon->coupon_id); ?>'>
									<?php 
									if( !empty($EM_Coupon->coupon_max) ){
										echo esc_html($EM_Coupon->get_count() .'/'. $EM_Coupon->coupon_max);
									}else{
										echo esc_html($EM_Coupon->get_count() .'/'. __('Unlimited','em-pro'));
									}
									?>
									</a>
								</td>                 
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else: ?>
				<br class="clear" />
				<p><?php _e('No coupons have been inserted yet!', 'dbem') ?></p>
				<?php endif; ?>
				
				<?php if ( !empty($coupons_nav) ) echo '<div class="tablenav">'. $coupons_nav .'</div>'; ?>
			</form>

		</div> <!-- wrap -->
		<?php
	}
	
	static function view_page(){
		global $EM_Notices, $EM_Coupon, $wpdb;
		//check that user can access this page
		if( is_object($EM_Coupon) && !$EM_Coupon->can_manage('manage_bookings','manage_others_bookings') ){
			?>
			<div class="wrap"><h2><?php _e('Unauthorized Access','dbem'); ?></h2><p><?php echo sprintf(__('You do not have the rights to manage this %s.','dbem'),__('coupon','em-pro')); ?></p></div>
			<?php
			return false;
		}elseif( !is_object($EM_Coupon) ){
			$EM_Coupon = new EM_Coupon();
		}
		$limit = ( !empty($_GET['limit']) ) ? $_GET['limit'] : 20;//Default limit
		$page = ( !empty($_GET['pno']) ) ? $_GET['pno']:1;
		$offset = ( $page > 1 ) ? ($page-1)*$limit : 0;
		//a bit hacky, but this is the only way at least for now
		$coupon_search = str_replace('a:1:{', '', serialize(array('coupon_code'=>$EM_Coupon->coupon_code)));
		$coupon_search = substr($coupon_search, 0, strlen($coupon_search)-1 );
		$bookings = $wpdb->get_col('SELECT booking_id FROM '.EM_BOOKINGS_TABLE." WHERE booking_meta LIKE '%{$coupon_search}%' LIMIT {$limit} OFFSET {$offset}");
		//FIXME : coupon count not syncing correctly, using this as a fallback
		$coupons_count = $wpdb->get_var('SELECT COUNT(*) FROM '.EM_BOOKINGS_TABLE." WHERE booking_meta LIKE '%{$coupon_search}%'");
		$bookings_count = 0;
		$EM_Bookings = array();
		foreach($bookings as $booking_id){ 
			$EM_Booking = em_get_booking($booking_id);
			if( !empty($EM_Booking->booking_meta['coupon']) ){
				$coupon = new EM_Coupon($EM_Booking->booking_meta['coupon']);
				if($EM_Coupon->coupon_code == $coupon->coupon_code && $EM_Coupon->coupon_id == $coupon->coupon_id){
					$bookings_count++;
					$EM_Bookings[] = $EM_Booking;
				}
			}
		}
		?>
		<div class='wrap nosubsub'>
			<div class="icon32" id="icon-bookings"><br></div>
			<h2><?php _e('Coupon Usage History','em-pro'); ?></h2>
			<?php echo $EM_Notices; ?>
			<p><?php echo sprintf(__('You are viewing the details of coupon %s - <a href="%s">edit</a>','em-pro'),'<code>'.$EM_Coupon->coupon_code.'</code>', add_query_arg(array('action'=>'edit'))); ?></p>
			<p>
				<strong><?php echo __('Uses', 'em-pro'); ?>:</strong> 
				<?php
				if( !empty($EM_Coupon->coupon_max) ){
					echo esc_html($coupons_count .' / '. $EM_Coupon->coupon_max);
				}else{
					echo esc_html($coupons_count .'/'. __('Unlimited','em-pro'));
				}
				?>
			</p>
			<?php if ( $coupons_count >= $limit ) : ?>
			<div class='tablenav'>
				<?php 
				$bookings_nav = em_admin_paginate($coupons_count, $limit, $page, array());
				echo $bookings_nav;
				?>
				<div class="clear"></div>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
			<?php if ( $bookings_count > 0 ) : ?>
			<div class='table-wrap'>
				<table id='dbem-bookings-table' class='widefat post '>
					<thead>
						<tr>
							<th class='manage-column' scope='col'><?php _e('Event', 'dbem'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Booker', 'dbem'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Spaces', 'dbem'); ?></th>
							<th><?php _e('Original Total Price','em-pro'); ?></th>
							<th><?php _e('Coupon Discount','em-pro'); ?></th>
							<th><?php _e('Final Price','em-pro'); ?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th class='manage-column' scope='col'><?php _e('Event', 'dbem'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Booker', 'dbem'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Spaces', 'dbem'); ?></th>
							<th><?php _e('Original Total Price','em-pro'); ?></th>
							<th><?php _e('Coupon Discount','em-pro'); ?></th>
							<th><?php _e('Final Price','em-pro'); ?></th>
							<th>&nbsp;</th>
						</tr>
					</tfoot>
					<tbody>
						<?php
						foreach($EM_Bookings as $EM_Booking){ 
							?>
							<tr>
								<td><?php echo $EM_Booking->output('#_BOOKINGSLINK') ?></td>
								<td><a href="<?php echo EM_ADMIN_URL; ?>&amp;page=events-manager-bookings&amp;person_id=<?php echo $EM_Booking->person_id; ?>"><?php echo $EM_Booking->person->get_name() ?></a></td>
								<td><?php echo $EM_Booking->get_spaces() ?></td>
								<td><?php echo em_get_currency_formatted($EM_Booking->booking_meta['original_price']); ?></td>
								<td><?php echo em_get_currency_formatted($EM_Booking->booking_meta['original_price'] - $EM_Booking->get_price()); ?> <em>(<?php echo $EM_Coupon->get_discount_text(); ?>)</em></td>
								<td><?php echo em_get_currency_formatted($EM_Booking->get_price()); ?></td>
								<td>										
									<?php
									$edit_url = em_add_get_params($_SERVER['REQUEST_URI'], array('booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null));
									?>
									<?php if( $EM_Booking->can_manage() ): ?>
									<a class="em-bookings-edit" href="<?php echo $edit_url; ?>"><?php _e('Edit/View','dbem'); ?></a>
									<?php endif; ?>
								</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</div> <!-- table-wrap -->
			<?php else: ?>
			<p><?php _e('Your coupon hasn\'t been used yet!','em-pro'); ?></p>
			<?php endif; ?>
		</div> <!-- wrap -->
		<?php
	}
	
	static function edit_form(){
		global $EM_Notices, $EM_Coupon;
		//check that user can access this page
		if( is_object($EM_Coupon) && !$EM_Coupon->can_manage('manage_bookings','manage_others_bookings') ){
			?>
			<div class="wrap"><h2><?php _e('Unauthorized Access','dbem'); ?></h2><p><?php echo sprintf(__('You do not have the rights to manage this %s.','dbem'),__('coupon','em-pro')); ?></p></div>
			<?php
			return false;
		}elseif( !is_object($EM_Coupon) ){
			$EM_Coupon = new EM_Coupon();
		}
		$required = "<i>(".__('required','dbem').")</i>";
		?>
		<div class='wrap nosubsub'>
			<div class="icon32" id="icon-bookings"><br></div>
			<h2><?php _e('Edit Coupon','em-pro'); ?></h2>
			<?php echo $EM_Notices; ?>
			<form id='coupon-form' method='post' action=''>
				<input type='hidden' name='action' value='coupon_save' />
				<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('coupon_save'); ?>' />
				<input type='hidden' name='coupon_id' value='<?php echo $EM_Coupon->coupon_id ?>'/>
				<table class="form-table">
					<tbody>
					<?php if( !get_option('dbem_multiple_bookings') ): ?>
    					<tr valign="top">
    						<th scope="row"><?php _e('Coupon Availability', 'em-pro') ?></th>
    						<td>
    							<select name="coupon_availability">
    								<option value=""><?php _e('Only on specific events that I own', 'em-pro') ?></option>
    								<option value="eventwide" <?php if($EM_Coupon->coupon_eventwide && !$EM_Coupon->coupon_sitewide) echo 'selected="selected"'; ?>><?php _e('All my events', 'em-pro') ?></option>
    								<?php if( current_user_can('manage_others_bookings') || is_super_admin() ): ?>
    								<option value="sitewide" <?php if($EM_Coupon->coupon_sitewide) echo 'selected="selected"'; ?>><?php _e('All events on this site', 'em-pro'); ?></option>
    								<?php endif; ?>
    							</select>
    							<br />
    							<em><?php _e('Choose whether to allow this coupon to be used only on events you choose, all your events or all events on this site.','em-pro'); ?></em>
    						</td>
    					</tr>
					<?php else: ?>
					   <tr><td colspan="2"><p><?php _e('This coupon will be available for all bookings made and discount is applied to the total price before checking out.','em-pro'); ?></p>
					<?php endif; ?>
					<tr valign="top">
						<th scope="row"><?php _e('Registered Users Only?', 'em-pro') ?></th>
							<td><input type="checkbox" name="coupon_private" value="1" <?php if($EM_Coupon->coupon_private) echo 'checked="checked"'; ?> />
							<br />
							<em><?php _e('If checked, only logged in users will be able to use this coupon.','em-pro'); ?></em>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Coupon Code', 'em-pro') ?></th>
							<td><input type="text" name="coupon_code" value="<?php echo esc_attr($EM_Coupon->coupon_code); ?>" />
							<br />
							<em><?php _e('This is the code you give to users for them to use when booking.','em-pro'); ?></em>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Name', 'em-pro') ?></th>
							<td><input type="text" name="coupon_name" value="<?php echo esc_attr($EM_Coupon->coupon_name); ?>" />
							<br />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Description', 'em-pro') ?></th>
							<td><input type="text" name="coupon_description" value="<?php echo esc_attr($EM_Coupon->coupon_description); ?>" />
							<br />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Total Coupons', 'em-pro') ?></th>
							<td><input type="text" name="coupon_max" value="<?php echo esc_attr($EM_Coupon->coupon_max); ?>" />
							<br />
							<em><?php _e('If set, this coupon will only be valid that many times.','em-pro'); ?></em>
						</td>
					</tr>
					<tbody class="em-date-range">
						<tr valign="top">
							<th scope="row"><?php _e('Start Date', 'em-pro') ?></th>
							<td>
								<input type="text" class="em-date-input-loc em-date-start" />
								<input type="hidden" class="em-date-input" name="coupon_start" value="<?php echo esc_attr(substr($EM_Coupon->coupon_start,0,10)); ?>" />
								<br />
								<em><?php _e('Coupons will only be valid from this date onwards.','em-pro'); ?></em>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php _e('End Date', 'em-pro') ?></th>
							<td>
								<input type="text" class="em-date-input-loc em-date-end" />
								<input type="hidden" class="em-date-input" name="coupon_end" value="<?php echo esc_attr(substr($EM_Coupon->coupon_end,0,10)); ?>" />
								<br />
								<em><?php _e('Coupons will not be valid after this date.','em-pro'); ?></em>
							</td>
						</tr>
					</tbody>
					<tr valign="top">
						<th scope="row"><?php _e('Discount Type', 'em-pro') ?></th>
						<td>
							<select name="coupon_type">
								<option value="%" <?php echo ($EM_Coupon->coupon_type == '%')?'selected="selected"':''; ?>><?php _e('Percentage','em-pro'); ?></option>
								<option value="#" <?php echo ($EM_Coupon->coupon_type == '#')?'selected="selected"':''; ?>><?php _e('Fixed Amount', 'em-pro'); ?></option>
							</select>
							<br />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Apply Before/After Tax', 'em-pro') ?></th>
						<td>
							<select name="coupon_tax">
								<option value="pre" <?php echo ($EM_Coupon->coupon_tax == 'pre')?'selected="selected"':''; ?>><?php _e('Before','em-pro'); ?></option>
								<option value="post" <?php echo ($EM_Coupon->coupon_tax == 'post')?'selected="selected"':''; ?>><?php _e('After', 'em-pro'); ?></option>
							</select>
							<br />
							<em><?php _e('Choose whether to apply this discount before or after tax has been added, if applicable.','em-pro'); ?></em>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Discount Amount', 'em-pro') ?></th>
							<td><input type="text" name="coupon_discount" value="<?php echo esc_attr($EM_Coupon->coupon_discount); ?>" />
							<br />
							<em><?php _e('Enter a number here only, decimals accepted.','em-pro'); ?></em>
						</td>
					</tr>
					</tbody>
				</table>				
				<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</p>
			</form>
		</div> <!-- wrap -->
		<?php
    }    
}
EM_Coupons_Admin::init();