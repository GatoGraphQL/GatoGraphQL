<?php 
/*
 * This page displays a printable view of bookings for a single event.
 * You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager/templates/ and modifying it however you need.
 * Here you can assume that $EM_Event is globally available with the right EM_Event object.
 */
global $EM_Event;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo sprintf(__('Bookings for %s','events-manager'), $EM_Event->name); ?></title>
	<link rel="stylesheet" href="<?php echo bloginfo('wpurl') ?>/wp-content/plugins/events-manager/includes/css/events_manager.css" type="text/css" media="screen" />
</head>
<body id="printable">
	<div id="container">
	<h1><?php echo sprintf(__('Bookings for %s','events-manager'), $EM_Event->name); ?></h1> 
	<p><?php echo $EM_Event->output("#d #M #Y"); ?></p>
	<p><?php echo $EM_Event->output("#_LOCATION, #_ADDRESS, #_TOWN"); ?></p>   
	<h2><?php _e('Bookings data', 'events-manager');?></h2>
	<table id="bookings-table">
		<tr>
			<th scope='col'><?php _e('Name', 'events-manager')?></th>
			<th scope='col'><?php _e('E-mail', 'events-manager')?></th>
			<th scope='col'><?php _e('Phone number', 'events-manager')?></th> 
			<th scope='col'><?php _e('Spaces', 'events-manager')?></th>
			<th scope='col'><?php _e('Comment', 'events-manager')?></th>
		</tr> 
		<?php foreach($EM_Event->get_bookings()->bookings as $EM_Booking) {       
			if( $EM_Booking->status == 1){
		    ?>
		<tr>
			
			<td><?php echo $EM_Booking->person->get_name() ?></td> 
			<td><?php echo $EM_Booking->person->user_email ?></td>
			<td><?php echo $EM_Booking->person->phone ?></td>
			<td class='spaces-number'><?php echo $EM_Booking->get_spaces() ?></td>
			<td><?php echo $EM_Booking->booking_comment ?></td> 
		</tr>
	   	<?php }} ?>
	  	<tr id='booked-spaces'>
			<td colspan='3'>&nbsp;</td>
			<td class='total-label'><?php _e('Booked', 'events-manager')?>:</td>
			<td class='spaces-number'><?php echo $EM_Event->get_bookings()->get_booked_spaces(); ?></td>
		</tr>
		<tr id='available-spaces'>
			<td colspan='3'>&nbsp;</td> 
			<td class='total-label'><?php _e('Available', 'events-manager')?>:</td>  
			<td class='spaces-number'><?php echo $EM_Event->get_bookings()->get_available_spaces(); ?></td>
		</tr>
	</table>  
	</div>
</body>
</html>