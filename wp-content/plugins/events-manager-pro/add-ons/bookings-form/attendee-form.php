<?php
/**
 * Extends the EM_Form to take into consideration the fact that many attendee sub-forms are submitted in one booking, unlike EM_Form which is geared to dealing with a single simple form submission.
 * @author marcus
 *
 */
class EM_Attendee_Form extends EM_Form {
	public $event_id;
	/**
	 * For use in output_field_input
	 * @var unknown_type
	 */
	public $attendee_number = false;
	
    /*
     * Extends the default function to search within a request variable which contains an array of attendee forms. The $ticket_id and $attendee_index is needed to locate the right form to process. 
     * @see EM_Form::get_post()
     */
    function get_post( $validate = false, $ticket_id = 0, $attendee_index = 0 ){
        $this->field_values = array();
    	foreach($this->form_fields as $field){
    		$fieldid = $field['fieldid'];
    		$value = '';
    		$request = $_REQUEST;
    		if( isset($_REQUEST['em_attendee_fields'][$ticket_id][$fieldid][$attendee_index]) && $_REQUEST['em_attendee_fields'][$ticket_id][$fieldid][$attendee_index] != '' ){
    			if( !is_array($_REQUEST['em_attendee_fields'][$ticket_id][$fieldid][$attendee_index])){
    				$this->field_values[$fieldid] = wp_kses_data(stripslashes($_REQUEST['em_attendee_fields'][$ticket_id][$fieldid][$attendee_index]));
    			}elseif( is_array($_REQUEST['em_attendee_fields'][$ticket_id][$fieldid][$attendee_index])){
    				$this->field_values[$fieldid] = $_REQUEST['em_attendee_fields'][$ticket_id][$fieldid][$attendee_index];
    			}
    		}
    		//dates and time are special
    		if( in_array($field['type'], array('date','time')) ){
    			if( !empty($_REQUEST['em_attendee_fields'][$ticket_id][$fieldid]['start'][$attendee_index]) ){
    				$this->field_values[$fieldid] = $_REQUEST['em_attendee_fields'][$ticket_id][$fieldid]['start'][$attendee_index];
    			}
    			if( $field['options_'.$field['type'].'_range'] && !empty($_REQUEST['em_attendee_fields'][$ticket_id][$fieldid]['end'][$attendee_index]) ){
    				$this->field_values[$fieldid] .= ','. $_REQUEST['em_attendee_fields'][$ticket_id][$fieldid]['end'][$attendee_index];
    			}
    		}
    	}
    	if( $validate ){
    		return $this->validate();
    	}
    	return true;
    }
    
    /* 
     * Overrides default method by search/replacing a specific string of text located in the input field name - [%n] - which represents the number of the attendee for that ticket
     * @see EM_Form::output_field_input()
     */
    function output_field_input($field, $post=true){
        $output = parent::output_field_input($field, $post);
    	if( in_array($field['type'], array('radio','checkboxes','multiselect')) && is_numeric($this->attendee_number) ){
    	    $output = str_replace('[%n]', '['.$this->attendee_number.']', $output);
    	}
    	return $output;
    }
    
}