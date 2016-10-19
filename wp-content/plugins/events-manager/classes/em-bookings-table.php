<?php
//Builds a table of bookings, still work in progress...
class EM_Bookings_Table{
	/**
	 * associative array of collumns that'll be shown in order from left to right
	 * 
	 * * key - collumn name in the databse, what will be used when searching
	 * * value - label for use in collumn headers 
	 * @var array
	 */
	public $cols = array('user_name','event_name','booking_spaces','booking_status','booking_price','actions');
	/**
	 * Asoociative array of available collumn keys and corresponding headers, which will be used to display this table of bookings
	 * @var unknown_type
	 */
	public $cols_template = array();
	public $sortable_cols = array('booking_date');
	/**
	 * Object we're viewing bookings in relation to.
	 * @var object
	 */
	public $cols_view;
	/**
	 * Index key used for looking up status information we're filtering in the booking table 
	 * @var string
	 */
	public $string = 'needs-attention';
	/**
	 * Associative array of status information.
	 * 
	 * * key - status index value
	 * * value - associative array containing keys
	 * ** label - the label for use in filter forms
	 * ** search - array or integer status numbers to search 
	 * 
	 * @var array
	 */
	public $statuses = array();
	/**
	 * Maximum number of rows to show
	 * @var int
	 */
	public $limit = 20;
	public $order = 'ASC';
	public $orderby = 'booking_name';
	public $page = 1;
	public $offset = 0;
	public $scope = 'future';
	public $show_tickets = false; 
	
	function __construct($show_tickets = false){
		$this->statuses = array(
			'all' => array('label'=>__('All','events-manager'), 'search'=>false),
			'pending' => array('label'=>__('Pending','events-manager'), 'search'=>0),
			'confirmed' => array('label'=>__('Confirmed','events-manager'), 'search'=>1),
			'cancelled' => array('label'=>__('Cancelled','events-manager'), 'search'=>3),
			'rejected' => array('label'=>__('Rejected','events-manager'), 'search'=>2),
			'needs-attention' => array('label'=>__('Needs Attention','events-manager'), 'search'=>array(0)),
			'incomplete' => array('label'=>__('Incomplete Bookings','events-manager'), 'search'=>array(0))
		);
		if( !get_option('dbem_bookings_approval') ){
			unset($this->statuses['pending']);
			unset($this->statuses['incomplete']);
			$this->statuses['confirmed']['search'] = array(0,1);
		}
		//Set basic vars
		$this->order = ( !empty($_REQUEST ['order']) && $_REQUEST ['order'] == 'DESC' ) ? 'DESC':'ASC';
		$this->orderby = ( !empty($_REQUEST ['orderby']) ) ? sanitize_sql_orderby($_REQUEST['orderby']):'booking_name';
		$this->limit = ( !empty($_REQUEST['limit']) && is_numeric($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;//Default limit
		$this->page = ( !empty($_REQUEST['pno']) && is_numeric($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
		$this->offset = ( $this->page > 1 ) ? ($this->page-1)*$this->limit : 0;
		$this->scope = ( !empty($_REQUEST['scope']) && array_key_exists($_REQUEST ['scope'], em_get_scopes()) ) ? sanitize_text_field($_REQUEST['scope']):get_option('dbem_default_bookings_search','future');
		$this->status = ( !empty($_REQUEST['status']) && array_key_exists($_REQUEST['status'], $this->statuses) ) ? sanitize_text_field($_REQUEST['status']):get_option('dbem_default_bookings_search','needs-attention');
		//build template of possible collumns
		$this->cols_template = apply_filters('em_bookings_table_cols_template', array(
			'user_name'=>__('Name','events-manager'),
			'first_name'=>__('First Name','events-manager'),
			'last_name'=>__('Last Name','events-manager'),
			'event_name'=>__('Event','events-manager'),
			'event_date'=>__('Event Date(s)','events-manager'),
			'event_time'=>__('Event Time(s)','events-manager'),
			'user_email'=>__('E-mail','events-manager'),
			'dbem_phone'=>__('Phone Number','events-manager'),
			'booking_spaces'=>__('Spaces','events-manager'),
			'booking_status'=>__('Status','events-manager'),
			'booking_date'=>__('Booking Date','events-manager'),
			'booking_price'=>__('Total','events-manager'),
			'booking_id'=>__('Booking ID','events-manager'),
			'booking_comment'=>__('Booking Comment','events-manager')
		), $this);
		$this->cols_tickets_template = apply_filters('em_bookings_table_cols_tickets_template', array(
			'ticket_name'=>__('Ticket Name','events-manager'),
			'ticket_description'=>__('Ticket Description','events-manager'),
			'ticket_price'=>__('Ticket Price','events-manager'),
			'ticket_id'=>__('Ticket ID','events-manager')
		), $this);
		//add tickets to template if we're showing rows by booking-ticket
		if( $show_tickets ){
			$this->show_tickets = true;
			$this->cols = array('user_name','event_name','ticket_name','ticket_price','booking_spaces','booking_status','actions');
			$this->cols_template = array_merge( $this->cols_template, $this->cols_tickets_template);
		}
		$this->cols_template['actions'] = __('Actions','events-manager');
		//calculate collumns if post requests		
		if( !empty($_REQUEST ['cols']) ){
		    if( is_array($_REQUEST ['cols']) ){
    		    array_walk($_REQUEST['cols'], 'sanitize_text_field');
    			$this->cols = $_REQUEST['cols'];
    		}else{
    			$this->cols = explode(',',sanitize_text_field($_REQUEST['cols']));
    		}
		}
		//load collumn view settings
		if( $this->get_person() !== false ){
			$this->cols_view = $this->get_person();
		}elseif( $this->get_ticket() !== false ){
			$this->cols_view = $this->get_ticket();
		}elseif( $this->get_event() !== false ){
			$this->cols_view = $this->get_event();
		}
		//save collumns depending on context and user preferences
		if( empty($_REQUEST['cols']) ){
			if(!empty($this->cols_view) && is_object($this->cols_view)){
				//check if user has settings for object type
				$settings = get_user_meta(get_current_user_id(), 'em_bookings_view-'.get_class($this->cols_view), true );
			}else{
				$settings = get_user_meta(get_current_user_id(), 'em_bookings_view', true );
			}
			if( !empty($settings) ){
				$this->cols = $settings;
			}
		}elseif( !empty($_REQUEST['cols']) && empty($_REQUEST['no_save']) ){ //save view settings for next time
		    if( !empty($this->cols_view) && is_object($this->cols_view) ){
				update_user_meta(get_current_user_id(), 'em_bookings_view-'.get_class($this->cols_view), $this->cols );
			}else{
				update_user_meta(get_current_user_id(), 'em_bookings_view', $this->cols );
			}
		}
		//clean any columns from saved views that no longer exist
		foreach($this->cols as $col_key => $col_name){
			if( !array_key_exists($col_name, $this->cols_template)){
				unset($this->cols[$col_key]);
			}
		}
		do_action('em_bookings_table', $this);
	}

	
	/**
	 * @return EM_Person|false
	 */
	function get_person(){
		global $EM_Person;
		if( !empty($this->person) && is_object($this->person) ){
			return $this->person;
		}elseif( !empty($_REQUEST['person_id']) && !empty($EM_Person) && is_object($EM_Person) ){
			return $EM_Person;
		}
		return false;
	}
	/**
	 * @return EM_Ticket|false
	 */
	function get_ticket(){
		global $EM_Ticket;
		if( !empty($this->ticket) && is_object($this->ticket) ){
			return $this->ticket;
		}elseif( !empty($EM_Ticket) && is_object($EM_Ticket) ){
			return $EM_Ticket;
		}
		return false;
	}
	/**
	 * @return $EM_Event|false
	 */
	function get_event(){
		global $EM_Event;
		if( !empty($this->event) && is_object($this->event) ){
			return $this->event;
		}elseif( !empty($EM_Event) && is_object($EM_Event) ){
			return $EM_Event;
		}
		return false;
	}
	
	/**
	 * Gets the bookings for this object instance according to its settings
	 * @param boolean $force_refresh
	 * @return EM_Bookings
	 */
	function get_bookings($force_refresh = true){	
		if( empty($this->bookings) || $force_refresh ){
			$this->events = array();
			$EM_Ticket = $this->get_ticket();
			$EM_Event = $this->get_event();
			$EM_Person = $this->get_person();
			if( $EM_Person !== false ){
				$args = array('person'=>$EM_Person->ID,'scope'=>$this->scope,'status'=>$this->get_status_search(),'order'=>$this->order,'orderby'=>$this->orderby);
				// Hack PoP Plug-in: allow $args to be overriden
				$args = apply_filters('gd_em_get_bookings_person_args', $args);
				$this->bookings_count = EM_Bookings::count($args);
				$this->bookings = EM_Bookings::get(array_merge($args, array('limit'=>$this->limit,'offset'=>$this->offset)));
				foreach($this->bookings->bookings as $EM_Booking){
					//create event
					if( !array_key_exists($EM_Booking->event_id,$this->events) ){
						$this->events[$EM_Booking->event_id] = new EM_Event($EM_Booking->event_id);
					}
				}
			}elseif( $EM_Ticket !== false ){
				//searching bookings with a specific ticket
				$args = array('ticket_id'=>$EM_Ticket->ticket_id, 'order'=>$this->order,'orderby'=>$this->orderby);
				$this->bookings_count = EM_Bookings::count($args);
				$this->bookings = EM_Bookings::get(array_merge($args, array('limit'=>$this->limit,'offset'=>$this->offset)));
				$this->events[$EM_Ticket->event_id] = $EM_Ticket->get_event();
			}elseif( $EM_Event !== false ){
				//bookings for an event
				$args = array('event'=>$EM_Event->event_id,'scope'=>false,'status'=>$this->get_status_search(),'order'=>$this->order,'orderby'=>$this->orderby);
				$args['owner'] = !current_user_can('manage_others_bookings') ? get_current_user_id() : false;
				// Hack PoP Plug-in: allow $args to be overriden
				$args = apply_filters('gd_em_get_bookings_event_args', $args);
				$this->bookings_count = EM_Bookings::count($args);
				$this->bookings = EM_Bookings::get(array_merge($args, array('limit'=>$this->limit,'offset'=>$this->offset)));
				$this->events[$EM_Event->event_id] = $EM_Event;
			}else{
				//all bookings for a status
				$args = array('status'=>$this->get_status_search(),'scope'=>$this->scope,'order'=>$this->order,'orderby'=>$this->orderby);
				$args['owner'] = !current_user_can('manage_others_bookings') ? get_current_user_id() : false;
				// Hack PoP Plug-in: allow $args to be overriden
				$args = apply_filters('gd_em_get_bookings_args', $args);
				$this->bookings_count = EM_Bookings::count($args);
				$this->bookings = EM_Bookings::get(array_merge($args, array('limit'=>$this->limit,'offset'=>$this->offset)));
				//Now let's create events and bookings for this instead of giving each booking an event
				foreach($this->bookings->bookings as $EM_Booking){
					//create event
					if( !array_key_exists($EM_Booking->event_id,$this->events) ){
						$this->events[$EM_Booking->event_id] = new EM_Event($EM_Booking->event_id);
					}
				}
			}
		}
		return $this->bookings;
	}
	
	function get_count(){
		return $this->bookings_count;
	}
	
	function get_status_search(){
		if(is_array($this->statuses[$this->status]['search'])){
			return implode(',',$this->statuses[$this->status]['search']);
		}
		return $this->statuses[$this->status]['search'];
	}
	
	function output(){
		do_action('em_bookings_table_header',$this); //won't be overwritten by JS	
		$this->output_overlays();
		$this->output_table();
		do_action('em_bookings_table_footer',$this); //won't be overwritten by JS	
	}
	
	function output_overlays(){
		$EM_Ticket = $this->get_ticket();
		$EM_Event = $this->get_event();
		$EM_Person = $this->get_person();
		?>
		<div id="em-bookings-table-settings" class="em-bookings-table-overlay" style="display:none;" title="<?php esc_attr_e('Bookings Table Settings','events-manager'); ?>">
			<form id="em-bookings-table-settings-form" class="em-bookings-table-form" action="" method="post">
				<p><?php _e('Modify what information is displayed in this booking table.','events-manager') ?></p>
				<div id="em-bookings-table-settings-form-cols">
					<p>
						<strong><?php _e('Columns to show','events-manager')?></strong><br />
						<?php _e('Drag items to or from the left column to add or remove them.','events-manager'); ?>
					</p>
					<ul id="em-bookings-cols-active" class="em-bookings-cols-sortable">
						<?php foreach( $this->cols as $col_key ): ?>
							<li class="ui-state-highlight">
								<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="<?php echo esc_attr($col_key); ?>" value="1" class="em-bookings-col-item" />
								<?php echo esc_html($this->cols_template[$col_key]); ?>
							</li>
						<?php endforeach; ?>
					</ul>			
					<ul id="em-bookings-cols-inactive" class="em-bookings-cols-sortable">
						<?php foreach( $this->cols_template as $col_key => $col_data ): ?>
							<?php if( !in_array($col_key, $this->cols) ): ?>
								<li class="ui-state-default">
									<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="<?php echo esc_attr($col_key); ?>" value="0" class="em-bookings-col-item"  />
									<?php echo esc_html($col_data); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</div>
			</form>
		</div>
		<div id="em-bookings-table-export" class="em-bookings-table-overlay" style="display:none;" title="<?php esc_attr_e('Export Bookings','events-manager'); ?>">
			<form id="em-bookings-table-export-form" class="em-bookings-table-form" action="" method="post">
				<p><?php esc_html_e('Select the options below and export all the bookings you have currently filtered (all pages) into a CSV spreadsheet format.','events-manager') ?></p>
				<?php if( !get_option('dbem_bookings_tickets_single') ): //single ticket mode means no splitting by ticket type ?>
					<p><?php esc_html_e('Split bookings by ticket type','events-manager')?> <input type="checkbox" name="show_tickets" value="1" />
					<a href="#" title="<?php esc_attr_e('If your events have multiple tickets, enabling this will show a separate row for each ticket within a booking.','events-manager'); ?>">?</a>
				<?php endif; ?>
				<?php do_action('em_bookings_table_export_options'); ?>
				<div id="em-bookings-table-settings-form-cols">
					<p><strong><?php esc_html_e('Columns to export','events-manager')?></strong></p>
					<ul id="em-bookings-export-cols-active" class="em-bookings-cols-sortable">
						<?php foreach( $this->cols as $col_key ): ?>
							<li class="ui-state-highlight">
								<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="cols[<?php echo esc_attr($col_key); ?>]" value="1" class="em-bookings-col-item" />
								<?php echo esc_html($this->cols_template[$col_key]); ?>
							</li>
						<?php endforeach; ?>
					</ul>			
					<ul id="em-bookings-export-cols-inactive" class="em-bookings-cols-sortable">
						<?php foreach( $this->cols_template as $col_key => $col_data ): ?>
							<?php if( !in_array($col_key, $this->cols) ): ?>
								<li class="ui-state-default">
									<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="cols[<?php echo esc_attr($col_key); ?>]" value="0" class="em-bookings-col-item"  />
									<?php echo esc_html($col_data); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if( !$this->show_tickets ): ?>
						<?php foreach( $this->cols_tickets_template as $col_key => $col_data ): ?>
							<?php if( !in_array($col_key, $this->cols) ): ?>
								<li class="ui-state-default <?php if(array_key_exists($col_key, $this->cols_tickets_template)) echo 'em-bookings-col-item-ticket'; ?>">
									<input id="em-bookings-col-<?php echo esc_attr($col_key); ?>" type="hidden" name="cols[<?php echo esc_attr($col_key); ?>]" value="0" class="em-bookings-col-item"  />
									<?php echo esc_html($col_data); ?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
				<?php if( $EM_Event !== false ): ?>
				<input type="hidden" name="event_id" value='<?php echo esc_attr($EM_Event->event_id); ?>' />
				<?php endif; ?>
				<?php if( $EM_Ticket !== false ): ?>
				<input type="hidden" name="ticket_id" value='<?php echo esc_attr($EM_Ticket->ticket_id); ?>' />
				<?php endif; ?>
				<?php if( $EM_Person !== false ): ?>
				<input type="hidden" name="person_id" value='<?php echo esc_attr($EM_Person->ID); ?>' />
				<?php endif; ?>
				<input type="hidden" name="scope" value='<?php echo esc_attr($this->scope); ?>' />
				<input type="hidden" name="status" value='<?php echo esc_attr($this->status); ?>' />
				<input type="hidden" name="no_save" value='1' />
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('export_bookings_csv'); ?>" />
				<input type="hidden" name="action" value="export_bookings_csv" />
			</form>
		</div>
		<br class="clear" />
		<?php
	}
	
	function output_table(){
		$EM_Ticket = $this->get_ticket();
		$EM_Event = $this->get_event();
		$EM_Person = $this->get_person();
		$this->get_bookings(true); //get bookings and refresh
		?>
		<div class='em-bookings-table em_obj' id="em-bookings-table">
			<form class='bookings-filter' method='post' action='<?php echo esc_url(bloginfo('wpurl')); ?>/wp-admin/edit.php'>
				<?php if( $EM_Event !== false ): ?>
				<input type="hidden" name="event_id" value='<?php echo esc_attr($EM_Event->event_id); ?>' />
				<?php endif; ?>
				<?php if( $EM_Ticket !== false ): ?>
				<input type="hidden" name="ticket_id" value='<?php echo esc_attr($EM_Ticket->ticket_id); ?>' />
				<?php endif; ?>
				<?php if( $EM_Person !== false ): ?>
				<input type="hidden" name="person_id" value='<?php echo esc_attr($EM_Person->ID); ?>' />
				<?php endif; ?>
				<input type="hidden" name="is_public" value="<?php echo ( !empty($_REQUEST['is_public']) || !is_admin() ) ? 1:0; ?>" />
				<input type="hidden" name="pno" value='<?php echo esc_attr($this->page); ?>' />
				<input type="hidden" name="order" value='<?php echo esc_attr($this->order); ?>' />
				<input type="hidden" name="orderby" value='<?php echo esc_attr($this->orderby); ?>' />
				<input type="hidden" name="_wpnonce" value="<?php echo ( !empty($_REQUEST['_wpnonce']) ) ? esc_attr($_REQUEST['_wpnonce']):wp_create_nonce('em_bookings_table'); ?>" />
				<input type="hidden" name="action" value="em_bookings_table" />
				<input type="hidden" name="cols" value="<?php echo esc_attr(implode(',', $this->cols)); ?>" />
				
				<div class='tablenav'>
					<div class="alignleft actions">
						<a href="#" class="em-bookings-table-export" id="em-bookings-table-export-trigger" rel="#em-bookings-table-export" title="<?php _e('Export these bookings.','events-manager'); ?>"></a>
						<a href="#" class="em-bookings-table-settings" id="em-bookings-table-settings-trigger" rel="#em-bookings-table-settings"></a>
						<?php if( $EM_Event === false ): ?>
						<select name="scope">
							<?php
							foreach ( em_get_scopes() as $key => $value ) {
								$selected = "";
								if ($key == $this->scope)
									$selected = "selected='selected'";
								echo "<option value='".esc_attr($key)."' $selected>".esc_html($value)."</option>  ";
							}
							?>
						</select>
						<?php endif; ?>
						<select name="limit">
							<option value="<?php echo esc_attr($this->limit) ?>"><?php echo esc_html(sprintf(__('%s Rows','events-manager'),$this->limit)); ?></option>
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
						<select name="status">
							<?php
							foreach ( $this->statuses as $key => $value ) {
								$selected = "";
								if ($key == $this->status)
									$selected = "selected='selected'";
								echo "<option value='".esc_attr($key)."' $selected>".esc_html($value['label'])."</option>  ";
							}
							?>
						</select>
						<input name="pno" type="hidden" value="1" />
						<input id="post-query-submit" class="button-secondary" type="submit" value="<?php esc_attr_e( 'Filter' )?>" />
						<?php if( $EM_Event !== false ): ?>
						<?php esc_html_e('Displaying Event','events-manager'); ?> : <?php echo esc_html($EM_Event->event_name); ?>
						<?php elseif( $EM_Person !== false ): ?>
						<?php esc_html_e('Displaying User','events-manager'); echo ' : '.esc_html($EM_Person->get_name()); ?>
						<?php endif; ?>
					</div>
					<?php 
					if ( $this->bookings_count >= $this->limit ) {
						$bookings_nav = em_admin_paginate( $this->bookings_count, $this->limit, $this->page, array(),'#%#%','#');
						echo $bookings_nav;
					}
					?>
				</div>
				<div class="clear"></div>
				<div class='table-wrap'>
				<table id='dbem-bookings-table' class='widefat post '>
					<thead>
						<tr>
							<?php /*						
							<th class='manage-column column-cb check-column' scope='col'>
								<input class='select-all' type="checkbox" value='1' />
							</th>
							*/ ?>
							<th class='manage-column' scope='col'><?php echo implode("</th><th class='manage-column' scope='col'>", $this->get_headers()); ?></th>
						</tr>
					</thead>
					<?php if( $this->bookings_count > 0 ): ?>
					<tbody>
						<?php 
						$rowno = 0;
						$event_count = (!empty($event_count)) ? $event_count:0;
						foreach ($this->bookings->bookings as $EM_Booking) {
							?>
							<tr>
								<?php  /*
								<th scope="row" class="check-column" style="padding:7px 0px 7px;"><input type='checkbox' value='<?php echo $EM_Booking->booking_id ?>' name='bookings[]'/></th>
								*/ 
								/* @var $EM_Booking EM_Booking */
								/* @var $EM_Ticket_Booking EM_Ticket_Booking */
								if( $this->show_tickets ){
									foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){
										?><td><?php echo implode('</td><td>', $this->get_row($EM_Ticket_Booking)); ?></td><?php
									}
								}else{
									?><td><?php echo implode('</td><td>', $this->get_row($EM_Booking)); ?></td><?php
								}
								?>
							</tr>
							<?php
						}
						?>
					</tbody>
					<?php else: ?>
						<tbody>
							<tr><td scope="row" colspan="<?php echo count($this->cols); ?>"><?php esc_html_e('No bookings.', 'events-manager'); ?></td></tr>
						</tbody>
					<?php endif; ?>
				</table>
				</div>
				<?php if( !empty($bookings_nav) && $this->bookings_count >= $this->limit ) : ?>
				<div class='tablenav'>
					<?php echo $bookings_nav; ?>
					<div class="clear"></div>
				</div>
				<?php endif; ?>
			</form>
		</div>
		<?php
	}
	
	function get_headers($csv = false){
		$headers = array();
		foreach($this->cols as $col){
			if( $col == 'actions' ){
				if( !$csv ) $headers[$col] = '&nbsp;';
			}elseif(array_key_exists($col, $this->cols_template)){
				/* for later - col ordering!
				if($this->orderby == $col){
					if($this->order == 'ASC'){
						$headers[] = '<a class="em-bookings-orderby" href="#'.$col.'">'.$this->cols_template[$col].' (^)</a>';
					}else{
						$headers[] = '<a class="em-bookings-orderby" href="#'.$col.'">'.$this->cols_template[$col].' (d)</a>';
					}
				}else{
					$headers[] = '<a class="em-bookings-orderby" href="#'.$col.'">'.$this->cols_template[$col].'</a>';
				}
				*/
				$headers[$col] = $this->cols_template[$col];
			}
		}
		return apply_filters('em_bookings_table_get_headers', $headers, $csv, $this);
	}
	
	function get_table(){
		
	}
	
	/**
	 * @param Object $object
	 * @return array()
	 */
	function get_row( $object, $csv = false ){
		/* @var $EM_Ticket EM_Ticket */
		/* @var $EM_Ticket_Booking EM_Ticket_Booking */
		/* @var $EM_Booking EM_Booking */
		if( get_class($object) == 'EM_Ticket_Booking' ){
			$EM_Ticket_Booking = $object;
			$EM_Ticket = $EM_Ticket_Booking->get_ticket();
			$EM_Booking = $EM_Ticket_Booking->get_booking();
		}else{
			$EM_Booking = $object;
		}
		$cols = array();
		foreach($this->cols as $col){
			$val = ''; //reset value
			//is col a user col or else?
			//TODO fix urls so this works in all pages in front as well
			if( $col == 'user_email' ){
				$val = $EM_Booking->get_person()->user_email;
			}elseif($col == 'dbem_phone'){
				$val = esc_html($EM_Booking->get_person()->phone);
			}elseif($col == 'user_name'){
				if( $csv || ( get_option('dbem_bookings_registration_disable') && $EM_Booking->get_person()->ID == get_option('dbem_bookings_registration_user') ) ){
					$val = $EM_Booking->get_person()->get_name();
				}else{
					$val = '<a href="'.esc_url(add_query_arg(array('person_id'=>$EM_Booking->person_id, 'event_id'=>null), $EM_Booking->get_event()->get_bookings_url())).'">'. $EM_Booking->person->get_name() .'</a>';
				}
			}elseif($col == 'first_name'){
				$val = esc_html($EM_Booking->get_person()->first_name);
			}elseif($col == 'last_name'){
				$val = esc_html($EM_Booking->get_person()->last_name);
			}elseif($col == 'event_name'){
				if( $csv ){
					$val = $EM_Booking->get_event()->event_name;
				}else{
					$val = '<a href="'.$EM_Booking->get_event()->get_bookings_url().'">'. esc_html($EM_Booking->get_event()->event_name) .'</a>';
				}
			}elseif($col == 'event_date'){
				$val = $EM_Booking->get_event()->output('#_EVENTDATES');
			}elseif($col == 'event_time'){
				$val = $EM_Booking->get_event()->output('#_EVENTTIMES');
			}elseif($col == 'booking_price'){
				if($this->show_tickets && !empty($EM_Ticket)){ 
					$val = em_get_currency_formatted(apply_filters('em_bookings_table_row_booking_price_ticket', $EM_Ticket_Booking->get_price(false,false, true), $EM_Booking, true));
				}else{
					$val = $EM_Booking->get_price(true);
				}
			}elseif($col == 'booking_status'){
				$val = $EM_Booking->get_status(true);
			}elseif($col == 'booking_date'){
				$val = date_i18n(get_option('dbem_date_format').' '. get_option('dbem_time_format'), $EM_Booking->timestamp);
			}elseif($col == 'actions' ){
				if( $csv ) continue; 
				$val = implode(' | ', $this->get_booking_actions($EM_Booking));
			}elseif( $col == 'booking_spaces' ){
				$val = ($this->show_tickets && !empty($EM_Ticket)) ? $EM_Ticket_Booking->get_spaces() : $EM_Booking->get_spaces();
			}elseif( $col == 'booking_id' ){
				$val = $EM_Booking->booking_id;
			}elseif( $col == 'ticket_name' && $this->show_tickets && !empty($EM_Ticket) ){
				$val = $csv ? $EM_Ticket->$col : esc_html($EM_Ticket->$col);
			}elseif( $col == 'ticket_description' && $this->show_tickets && !empty($EM_Ticket) ){
				$val = $csv ? $EM_Ticket->$col : esc_html($EM_Ticket->$col);
			}elseif( $col == 'ticket_price' && $this->show_tickets && !empty($EM_Ticket) ){
				$val = $EM_Ticket->get_price(true);
			}elseif( $col == 'ticket_id' && $this->show_tickets && !empty($EM_Ticket) ){
				$val = $EM_Ticket->ticket_id;
			}elseif( $col == 'booking_comment' ){
				$val = $csv ? $EM_Booking->booking_comment : esc_html($EM_Booking->booking_comment);
			}
			//use this 
			$val = apply_filters('em_bookings_table_rows_col_'.$col, $val, $EM_Booking, $this, $csv, $object);
			$cols[] = apply_filters('em_bookings_table_rows_col', $val, $col, $EM_Booking, $this, $csv, $object); //deprecated, use the above filter instead for better performance
		}
		//clean up the cols to prevent nasty html or xss
		global $allowedposttags;
		foreach($cols as $key => $col){
			$cols[$key] = wp_kses($col, $allowedposttags);
		}
		return $cols;
	}
	
	function get_row_csv($EM_Booking){
	    $row = $this->get_row($EM_Booking, true);
	    foreach($row as $k=>$v) $row[$k] = html_entity_decode($v); //remove things like &amp; which may have been saved to the DB directly
	    return $row;
	}
	
	/**
	 * @param EM_Booking $EM_Booking
	 * @return mixed
	 */
	function get_booking_actions($EM_Booking){
		$booking_actions = array();
		$url = $EM_Booking->get_event()->get_bookings_url();	
		switch($EM_Booking->booking_status){
			case 0: //pending
				if( get_option('dbem_bookings_approval') ){
					$booking_actions = array(
						'approve' => '<a class="em-bookings-approve" href="'.em_add_get_params($url, array('action'=>'bookings_approve', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Approve','events-manager').'</a>',
						'reject' => '<a class="em-bookings-reject" href="'.em_add_get_params($url, array('action'=>'bookings_reject', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Reject','events-manager').'</a>',
						'delete' => '<span class="trash"><a class="em-bookings-delete" href="'.em_add_get_params($url, array('action'=>'bookings_delete', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Delete','events-manager').'</a></span>',
						'edit' => '<a class="em-bookings-edit" href="'.em_add_get_params($EM_Booking->get_event()->get_bookings_url(), array('booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null)).'">'.__('Edit/View','events-manager').'</a>',
					);
					break;
				}//if approvals are off, treat as a 1
			case 1: //approved
				$booking_actions = array(
					'unapprove' => '<a class="em-bookings-unapprove" href="'.em_add_get_params($url, array('action'=>'bookings_unapprove', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Unapprove','events-manager').'</a>',
					'reject' => '<a class="em-bookings-reject" href="'.em_add_get_params($url, array('action'=>'bookings_reject', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Reject','events-manager').'</a>',
					'delete' => '<span class="trash"><a class="em-bookings-delete" href="'.em_add_get_params($url, array('action'=>'bookings_delete', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Delete','events-manager').'</a></span>',
					'edit' => '<a class="em-bookings-edit" href="'.em_add_get_params($EM_Booking->get_event()->get_bookings_url(), array('booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null)).'">'.__('Edit/View','events-manager').'</a>',
				);
				break;
			case 2: //rejected
				$booking_actions = array(
					'approve' => '<a class="em-bookings-approve" href="'.em_add_get_params($url, array('action'=>'bookings_approve', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Approve','events-manager').'</a>',
					'delete' => '<span class="trash"><a class="em-bookings-delete" href="'.em_add_get_params($url, array('action'=>'bookings_delete', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Delete','events-manager').'</a></span>',
					'edit' => '<a class="em-bookings-edit" href="'.em_add_get_params($EM_Booking->get_event()->get_bookings_url(), array('booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null)).'">'.__('Edit/View','events-manager').'</a>',
				);
				break;
			case 3: //cancelled
			case 4: //awaiting online payment - similar to pending but always needs approval in EM Free
			case 5: //awaiting payment - similar to pending but always needs approval in EM Free
				$booking_actions = array(
					'approve' => '<a class="em-bookings-approve" href="'.em_add_get_params($url, array('action'=>'bookings_approve', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Approve','events-manager').'</a>',
					'delete' => '<span class="trash"><a class="em-bookings-delete" href="'.em_add_get_params($url, array('action'=>'bookings_delete', 'booking_id'=>$EM_Booking->booking_id)).'">'.__('Delete','events-manager').'</a></span>',
					'edit' => '<a class="em-bookings-edit" href="'.em_add_get_params($EM_Booking->get_event()->get_bookings_url(), array('booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null)).'">'.__('Edit/View','events-manager').'</a>',
				);
				break;
				
		}
		if( !get_option('dbem_bookings_approval') ) unset($booking_actions['unapprove']);
		$booking_actions = apply_filters('em_bookings_table_booking_actions_'.$EM_Booking->booking_status ,$booking_actions, $EM_Booking);
		return apply_filters('em_bookings_table_cols_col_action', $booking_actions, $EM_Booking);
	}
}
?>