<?php
if(!class_exists('EM_Gateways_Transactions')) {
class EM_Gateways_Transactions{
	var $limit = 20;
	var $total_transactions = 0;
	
	function __construct(){
		$this->order = ( !empty($_REQUEST ['order']) ) ? $_REQUEST ['order']:'ASC';
		$this->orderby = ( !empty($_REQUEST ['order']) ) ? $_REQUEST ['order']:'booking_name';
		$this->limit = ( !empty($_REQUEST['limit']) ) ? $_REQUEST['limit'] : 20;//Default limit
		$this->page = ( !empty($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
		$this->gateway = !empty($_REQUEST['gateway']) ? $_REQUEST['gateway']:false;
		//Add options and tables to EM admin pages
		if( current_user_can('manage_others_bookings') ){
			add_action('em_bookings_dashboard', array(&$this, 'output'),10,1);
			add_action('em_bookings_ticket_footer', array(&$this, 'output'),10,1);
			add_action('em_bookings_single_footer', array(&$this, 'output'),10,1);
			add_action('em_bookings_person_footer', array(&$this, 'output'),10,1);
			add_action('em_bookings_event_footer', array(&$this, 'output'),10,1);
		}
		//Booking Tables UI
		add_filter('em_bookings_table_rows_col', array(&$this,'em_bookings_table_rows_col'),10,5);
		add_filter('em_bookings_table_cols_template', array(&$this, 'em_bookings_table_cols_template'),10,2);
		add_action('wp_ajax_em_transactions_table', array(&$this, 'ajax'),10,1);
	}
	
	function ajax(){
		if( wp_verify_nonce($_REQUEST['_wpnonce'],'em_transactions_table') ){
			//Get the context
			global $EM_Event, $EM_Booking, $EM_Ticket, $EM_Person;
			em_load_event();
			$context = false;
			if( !empty($_REQUEST['booking_id']) && is_object($EM_Booking) && $EM_Booking->can_manage('manage_bookings','manage_others_bookings') ){
				$context = $EM_Booking;
			}elseif( !empty($_REQUEST['event_id']) && is_object($EM_Event) && $EM_Event->can_manage('manage_bookings','manage_others_bookings') ){
				$context = $EM_Event;
			}elseif( !empty($_REQUEST['person_id']) && is_object($EM_Person) && current_user_can('manage_bookings') ){
				$context = $EM_Person;
			}elseif( !empty($_REQUEST['ticket_id']) && is_object($EM_Ticket) && $EM_Ticket->can_manage('manage_bookings','manage_others_bookings') ){
				$context = $EM_Ticket;
			}			
			echo $this->mytransactions($context);
			exit;
		}
	}
	
	function output( $context = false ) {
		global $page, $action, $wp_query;
		?>
		<div class="wrap">
		<div class="icon32" id="icon-bookings"><br></div>
		<h2><?php echo __('Transactions','dbem'); ?></h2>
		<?php $this->mytransactions($context); ?>
		<script type="text/javascript">
			jQuery(document).ready( function($){
				//Pagination link clicks
				$('#em-transactions-table .tablenav-pages a').live('click', function(){
					var el = $(this);
					var form = el.parents('#em-transactions-table form.transactions-filter');
					//get page no from url, change page, submit form
					var match = el.attr('href').match(/#[0-9]+/);
					if( match != null && match.length > 0){
						var pno = match[0].replace('#','');
						form.find('input[name=pno]').val(pno);
					}else{
						form.find('input[name=pno]').val(1);
					}
					form.trigger('submit');
					return false;
				});
				//Widgets and filter submissions
				$('#em-transactions-table form.transactions-filter').live('submit', function(e){
					var el = $(this);			
					el.parents('#em-transactions-table').find('.table-wrap').first().append('<div id="em-loading" />');
					$.get( EM.ajaxurl, el.serializeArray(), function(data){
						el.parents('#em-transactions-table').first().replaceWith(data);
					});
					return false;
				});
			});
		</script>
		</div>
		<?php
	}

	function mytransactions($context=false) {
		global $EM_Person;
		$transactions = $this->get_transactions($context);
		$total = $this->total_transactions;

		$columns = array();

		$columns['event'] = __('Event','em-pro');
		$columns['user'] = __('User','em-pro');
		$columns['date'] = __('Date','em-pro');
		$columns['amount'] = __('Amount','em-pro');
		$columns['transid'] = __('Transaction id','em-pro');
		$columns['gateway'] = __('Gateway','em-pro');
		$columns['status'] = __('Status','em-pro');
		$columns['note'] = __('Notes','em-pro');
		$columns['actions'] = '';

		$trans_navigation = paginate_links( array(
			'base' => add_query_arg( 'paged', '%#%' ),
			'format' => '',
			'total' => ceil($total / 20),
			'current' => $this->page
		));
		?>
		<div id="em-transactions-table" class="em_obj">
		<form id="em-transactions-table-form" class="transactions-filter" action="" method="post">
			<?php if( is_object($context) && get_class($context)=="EM_Event" ): ?>
			<input type="hidden" name="event_id" value='<?php echo $context->event_id ?>' />
			<?php elseif( is_object($context) && get_class($context)=="EM_Person" ): ?>
			<input type="hidden" name="person_id" value='<?php echo $context->person_id ?>' />
			<?php endif; ?>
			<input type="hidden" name="pno" value='<?php echo $this->page ?>' />
			<input type="hidden" name="order" value='<?php echo $this->order ?>' />
			<input type="hidden" name="orderby" value='<?php echo $this->orderby ?>' />
			<input type="hidden" name="_wpnonce" value="<?php echo ( !empty($_REQUEST['_wpnonce']) ) ? esc_attr($_REQUEST['_wpnonce']):wp_create_nonce('em_transactions_table'); ?>" />
			<input type="hidden" name="action" value="em_transactions_table" />
			
			<div class="tablenav">
				<div class="alignleft actions">
					<select name="limit">
						<option value="<?php echo $this->limit ?>"><?php echo sprintf(__('%s Rows','dbem'),$this->limit); ?></option>
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
						<option value="100">100</option>
					</select>
					<select name="gateway">
						<option value="">All</option>
						<?php
						global $EM_Gateways;
						foreach ( $EM_Gateways as $EM_Gateway ) {
							?><option value='<?php echo $EM_Gateway->gateway ?>' <?php if($EM_Gateway->gateway == $this->gateway) echo "selected='selected'"; ?>><?php echo $EM_Gateway->title ?></option><?php
						}
						?>
					</select>
					<input id="post-query-submit" class="button-secondary" type="submit" value="<?php _e ( 'Filter' )?>" />
					<?php if( is_object($context) && get_class($context)=="EM_Event" ): ?>
					<?php _e('Displaying Event','dbem'); ?> : <?php echo $context->event_name; ?>
					<?php elseif( is_object($context) && get_class($context)=="EM_Person" ): ?>
					<?php _e('Displaying User','dbem'); echo ' : '.$context->get_name(); ?>
					<?php endif; ?>
				</div>
				<?php 
				if ( $this->total_transactions >= $this->limit ) {
					echo em_admin_paginate( $this->total_transactions, $this->limit, $this->page, array(),'#%#%','#');
				}
				?>
			</div>

			<div class="table-wrap">
			<table cellspacing="0" class="widefat">
				<thead>
				<tr>
				<?php
					foreach($columns as $key => $col) {
						?>
						<th style="" class="manage-column column-<?php echo $key; ?>" id="<?php echo $key; ?>" scope="col"><?php echo $col; ?></th>
						<?php
					}
				?>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<?php
						reset($columns);
						foreach($columns as $key => $col) {
							?>
							<th style="" class="manage-column column-<?php echo $key; ?>" id="<?php echo $key; ?>" scope="col"><?php echo $col; ?></th>
							<?php
						}
					?>
				</tr>
				</tfoot>

				<tbody>
					<?php
						echo $this->print_transactions($transactions);
					?>

				</tbody>
			</table>
			</div>
		</form>
		</div>
		<?php
	}

	// Hack PoP Plug-in: it is 9 columns by default, not 7		
	function print_transactions($transactions, $columns=9){
		ob_start();
		if($transactions) {
			foreach($transactions as $key => $transaction) {
				?>
				<tr valign="middle" class="alternate">
					<td>
						<?php
							$EM_Booking = em_get_booking($transaction->booking_id);
							if( get_class($EM_Booking) == 'EM_Multiple_Booking' ){
								$link = em_add_get_params($EM_Booking->get_admin_url(), array('booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null));
								echo '<a href="'.$link.'">'.$EM_Booking->get_event()->event_name.'</a>';
							}else{
								echo '<a href="'.$EM_Booking->get_event()->get_bookings_url().'">'.$EM_Booking->get_event()->event_name.'</a>';
							}
						?>
					</td>
					<td>
						<?php
							echo '<a href="'.$EM_Booking->get_person()->get_bookings_url().'">'. $EM_Booking->person->get_name() .'</a>';
						?>
					</td>
					<td class="column-date">
						<?php
							echo mysql2date(get_option('dbem_date_format'), $transaction->transaction_timestamp);
						?>
					</td>
					<td class="column-amount">
						<?php
							$amount = $transaction->transaction_total_amount;
							echo $transaction->transaction_currency;
							echo "&nbsp;" . number_format($amount, 2, '.', ',');
						?>
					</td>
					<td class="column-gateway-trans-id">
						<?php
							if(!empty($transaction->transaction_gateway_id)) {
								echo $transaction->transaction_gateway_id;
							} else {
								echo __('None yet','em-pro');
							}
						?>
					</td>
					<td class="column-gateway">
						<?php
							if(!empty($transaction->transaction_gateway)) {
								echo $transaction->transaction_gateway;
							} else {
								echo __('None yet','em-pro');
							}
						?>
					</td>
					<td class="column-trans-status">
						<?php
							if(!empty($transaction->transaction_status)) {
								echo $transaction->transaction_status;
							} else {
								echo __('None yet','em-pro');
							}
						?>
					</td>
					<td class="column-trans-note-id">
						<?php
							if(!empty($transaction->transaction_note)) {
								echo esc_html($transaction->transaction_note);
							} else {
								echo __('None','em-pro');
							}
						?>
					</td>
					<td class="column-trans-note-id">
						<?php if( $EM_Booking->can_manage() ): ?>
						<span class="trash"><a class="em-transaction-delete" href="<?php echo em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>'transaction_delete', 'txn_id'=>$transaction->transaction_id, '_wpnonce'=>wp_create_nonce('transaction_delete_'.$transaction->transaction_id.'_'.get_current_user_id()))); ?>"><?php _e('Delete','dbem'); ?></a></span>
						<?php endif; ?>
					</td>
			    </tr>
				<?php
			}
		} else {
			// Hack PoP Plug-in: $columns is already a number
			//$columncount = count($columns);
			$columncount = $columns;
			?>
			<tr valign="middle" class="alternate" >
				<td colspan="<?php echo $columncount; ?>" scope="row"><?php _e('No Transactions','em-pro'); ?></td>
		    </tr>
			<?php
		}
		return ob_get_clean();
	}
	
	/**
	 * @param mixed $context
	 * @return stdClass|false
	 */
	function get_transactions($context=false) {
		global $wpdb;
		$join = '';
		$conditions = array();
		$table = EM_BOOKINGS_TABLE;
		//we can determine what to search for, based on if certain variables are set.
		if( is_object($context) && (get_class($context)=="EM_Booking" || get_class($context)=="EM_Multiple_Booking" ) && $context->can_manage('manage_bookings','manage_others_bookings') ){
			$conditions[] = "tx.booking_id = ".$context->booking_id;
		}elseif( is_object($context) && get_class($context)=="EM_Event" && $context->can_manage('manage_bookings','manage_others_bookings') ){
			$join = " JOIN $table ON $table.booking_id=tx.booking_id";	
			$conditions[] = "event_id = ".$context->event_id;		
		}elseif( is_object($context) && get_class($context)=="EM_Person" ){
			//FIXME peole could potentially view other's txns like this
			$join = " JOIN $table ON $table.booking_id=tx.booking_id";
			$conditions[] = "person_id = ".$context->ID;			
		}elseif( is_object($context) && get_class($context)=="EM_Ticket" && $context->can_manage('manage_bookings','manage_others_bookings') ){
			$booking_ids = array();
			foreach($context->get_bookings()->bookings as $EM_Booking){
				$booking_ids[] = $EM_Booking->booking_id;
			}
			if( count($booking_ids) > 0 ){
				$conditions[] = "tx.booking_id IN (".implode(',', $booking_ids).")";
			}else{
				return new stdClass();
			}			
		}
		if( is_multisite() && (!is_main_site() || is_admin()) ){ //if not main blog, we show only blog specific booking info
			global $blog_id;
			$join = " JOIN $table ON $table.booking_id=tx.booking_id";
			$conditions[] = "$table.booking_id IN (SELECT $table.booking_id FROM $table, ".EM_EVENTS_TABLE." e WHERE $table.event_id=e.event_id AND e.blog_id=".$blog_id.")";
		}
		//filter by gateway
		if( !empty($this->gateway) ){
			$conditions[] = $wpdb->prepare('transaction_gateway = %s',$this->gateway);
		}
		//build conditions string
		$condition = (!empty($conditions)) ? "WHERE ".implode(' AND ', $conditions):'';
		$offset = ( $this->page > 1 ) ? ($this->page-1)*$this->limit : 0;		
		$sql = $wpdb->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM ".EM_TRANSACTIONS_TABLE." tx $join $condition ORDER BY transaction_id DESC  LIMIT %d, %d", $offset, $this->limit );
		$return = $wpdb->get_results( $sql );
		$this->total_transactions = $wpdb->get_var( "SELECT FOUND_ROWS();" );
		return $return;
	}	

	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	
	function em_bookings_table_rows_col($value, $col, $EM_Booking, $EM_Bookings_Table, $csv){
		global $EM_Event;
		if( $col == 'gateway_txn_id' ){
			//get latest transaction with an ID
			$old_limit = $this->limit;
			$old_orderby = $this->orderby;
			$this->limit = 1;
			$this->orderby = 'booking_date';
			$transactions = $this->get_transactions($EM_Booking);
			if(count($transactions) > 0){
				$value = $transactions[0]->transaction_gateway_id;
			}
			$this->limit = $old_limit;
			$this->orderby = $old_orderby;
		}
		return $value;
	}
	
	function em_bookings_table_cols_template($template, $EM_Bookings_Table){
		$template['gateway_txn_id'] = __('Transaction ID','em-pro');
		return $template;
	}
}
}

/**
 * Checks for any deletions requested 
 */
function emp_transactions_init(){
	global $EM_Gateways_Transactions;
	$EM_Gateways_Transactions = new EM_Gateways_Transactions();
	
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'transaction_delete' && wp_verify_nonce($_REQUEST['_wpnonce'], 'transaction_delete_'.$_REQUEST['txn_id'].'_'.get_current_user_id()) ){
		//get booking from transaction, ensure user can manage it before deleting
		global $wpdb;
		$booking_id = $wpdb->get_var('SELECT booking_id FROM '.EM_TRANSACTIONS_TABLE." WHERE transaction_id='".$_REQUEST['txn_id']."'");
		if( !empty($booking_id) ){
			$EM_Booking = em_get_booking($booking_id);
			if( (!empty($EM_Booking->booking_id) && $EM_Booking->can_manage()) || is_super_admin() ){
				//all good, delete it
				$wpdb->query('DELETE FROM '.EM_TRANSACTIONS_TABLE." WHERE transaction_id='".$_REQUEST['txn_id']."'");
				_e('Transaction deleted','em-pro');
				exit();
			}
		}
		_e('Transaction could not be deleted', 'em-pro');
		exit();
	}
}
add_action('init','emp_transactions_init');