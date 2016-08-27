<?php 
/* 
 * This file generates the input fields for an event with a single ticket and settings set to not show a table for single tickets (default setting)
 * If you want to add to this form this, you'd be better off hooking into the actions below.
 */
/* @var $EM_Ticket EM_Ticket */
/* @var $EM_Event EM_Event */
global $allowedposttags;
do_action('em_booking_form_ticket_header', $EM_Ticket); //do not delete
/*
 * This variable can be overridden, by hooking into the em_booking_form_tickets_cols filter and adding your collumns into this array.
 * Then, you should create a em_booking_form_ticket_field_arraykey action for your collumn data, which will pass a ticket and event object.
 */
$collumns = $EM_Event->get_tickets()->get_ticket_collumns(); //array of collumn type => title
foreach( $collumns as $type => $name ): ?>
	<?php
	//output collumn by type, or call a custom action 
	switch($type){
		case 'type':
			if(!empty($EM_Ticket->ticket_description)){ //show description if there is one
				?><p class="ticket-desc"><?php echo wp_kses($EM_Ticket->ticket_description,$allowedposttags); ?></p><?php
			}
			break;
		case 'price':
			?><p class="ticket-price"><label><?php echo $name; ?></label><strong><?php echo $EM_Ticket->get_price(true); ?></strong></p><?php
			break;
		case 'spaces':
			if( $EM_Ticket->get_available_spaces() > 1 && ( empty($EM_Ticket->ticket_max) || $EM_Ticket->ticket_max > 1 ) ): //more than one space available ?>				
				<p class="em-tickets-spaces">
					<label for='em_tickets'><?php echo $name; ?></label>
					<?php 
						$default = !empty($_REQUEST['em_tickets'][$EM_Ticket->ticket_id]['spaces']) ? $_REQUEST['em_tickets'][$EM_Ticket->ticket_id]['spaces']:0;
						$spaces_options = $EM_Ticket->get_spaces_options(false,$default);
						if( $spaces_options ){
							echo $spaces_options;
						}else{
							echo "<strong>".__('N/A','events-manager')."</strong>";
						}
					?>
				</p>
				<?php do_action('em_booking_form_ticket_spaces', $EM_Ticket); //do not delete ?>
			<?php else: //if only one space or ticket max spaces per booking is 1 ?>
				<input type="hidden" name="em_tickets[<?php echo $EM_Ticket->ticket_id ?>][spaces]" value="1" class="em-ticket-select" />
				<?php do_action('em_booking_form_ticket_spaces', $EM_Ticket); //do not delete ?>
			<?php endif;
			break;
		default:
			do_action('em_booking_form_ticket_field_'.$type, $EM_Ticket, $EM_Event);
			break;
	}
endforeach; ?>
<?php do_action('em_booking_form_ticket_footer', $EM_Ticket); //do not delete ?>