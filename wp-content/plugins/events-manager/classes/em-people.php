<?php
class EM_People extends EM_Object {
	
	/**
	 * Handles the action of someone being deleted on WordPress
	 * @param int $id
	 */
	public static function delete_user( $id ){
		global $wpdb;
		if( $_REQUEST['delete_option'] == 'reassign' && is_numeric($_REQUEST['reassign_user']) ){
			$wpdb->update(EM_EVENTS_TABLE, array('event_owner'=>$_REQUEST['reassign_user']), array('event_owner'=>$id));
		}else{
			//User is being deleted, so we delete their events and cancel their bookings.
			$wpdb->query("DELETE FROM ".EM_EVENTS_TABLE." WHERE event_owner=$id");
		}
		//delete the booking completely
		if( !get_option('dbem_bookings_registration_disable') || $id != get_option('dbem_bookings_registration_user') ){
		    $EM_Person = new EM_Person();
		    $EM_Person->ID = $EM_Person->person_id = $id;
		    foreach( $EM_Person->get_bookings() as $EM_Booking){
		        $EM_Booking->manage_override = true;
		        $EM_Booking->delete();
		    }		    
		}else{ //user is the no-user mode assigned user, so don't delete all the guest bookings, in case of mistake.
			$wpdb->update(EM_BOOKINGS_TABLE, array('booking_status'=>3, 'person_id'=>0, 'booking_comment'=>__('User deleted by administrators','events-manager')), array('person_id'=>$id));
		}
	}
	
	/**
	 * Adds phone number to contact info of users, compatible with previous phone field method
	 * @param $array
	 * @return array
	 */
	public static function user_contactmethods($array){
		$array['dbem_phone'] = __('Phone','events-manager') . ' <span class="description">('. __('Events Manager','events-manager') .')</span>';
		return $array;
	}	
}
add_action('delete_user', array('EM_People','delete_user'),10,1);
add_filter( 'user_contactmethods' , array('EM_People','user_contactmethods'),10,1);
?>