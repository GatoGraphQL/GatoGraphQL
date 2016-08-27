<?php 
/* 
 * This file generates a tabular list of tickets for the event booking forms with input values for choosing ticket spaces.
 * If you want to add to this form this, you'd be better off hooking into the actions below.
 */
/* @var $EM_Event EM_Event */
global $allowedposttags;
$EM_Tickets = $EM_Event->get_bookings()->get_tickets(); //already instantiated, so should be a quick retrieval.
/*
 * This variable can be overridden, by hooking into the em_booking_form_tickets_cols filter and adding your collumns into this array.
 * Then, you should create a em_booking_form_tickets_col_arraykey action for your collumn data, which will pass a ticket and event object.
 */
$collumns = $EM_Tickets->get_ticket_collumns(); //array of collumn type => title
?>
<table class="em-tickets" cellspacing="0" cellpadding="0">
	<tr>
		<?php foreach($collumns as $type => $name): ?>
		<th class="em-bookings-ticket-table-<?php echo $type; ?>"><?php echo $name; ?></th>
		<?php endforeach; ?>
	</tr>
	<?php foreach( $EM_Tickets->tickets as $EM_Ticket ): /* @var $EM_Ticket EM_Ticket */ ?>
		<?php if( $EM_Ticket->is_displayable() ): ?>
			<?php do_action('em_booking_form_tickets_loop_header', $EM_Ticket); //do not delete ?>
			<tr class="em-ticket" id="em-ticket-<?php echo $EM_Ticket->ticket_id; ?>">
				<?php foreach( $collumns as $type => $name ): ?>
					<?php
					//output collumn by type, or call a custom action 
					switch($type){
						case 'type':
							?>
							<td class="em-bookings-ticket-table-type"><?php echo wp_kses_data($EM_Ticket->ticket_name); ?><?php if(!empty($EM_Ticket->ticket_description)) :?><br><span class="ticket-desc"><?php echo wp_kses($EM_Ticket->ticket_description,$allowedposttags); ?></span><?php endif; ?></td>
							<?php
							break;
						case 'price':
							?>
							<td class="em-bookings-ticket-table-price"><?php echo $EM_Ticket->get_price(true); ?></td>
							<?php
							break;
						case 'spaces':
							?>
							<td class="em-bookings-ticket-table-spaces">
								<?php 
									$default = !empty($_REQUEST['em_tickets'][$EM_Ticket->ticket_id]['spaces']) ? $_REQUEST['em_tickets'][$EM_Ticket->ticket_id]['spaces']:0;
									$spaces_options = $EM_Ticket->get_spaces_options(true,$default);
									echo ( $spaces_options ) ? $spaces_options:"<strong>".__('N/A','events-manager')."</strong>";
								?>
							</td>
							<?php
							break;
						default:
							do_action('em_booking_form_tickets_col_'.$type, $EM_Ticket, $EM_Event);
							break;
					}
					?>
				<?php endforeach; ?>
			</tr>		
			<?php do_action('em_booking_form_tickets_loop_footer', $EM_Ticket); //do not delete ?>
		<?php endif; ?>
	<?php endforeach; ?>
</table>