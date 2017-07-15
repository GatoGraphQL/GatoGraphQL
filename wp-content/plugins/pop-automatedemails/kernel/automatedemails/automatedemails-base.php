<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_AutomatedEmailsBase {

    function __construct() {
    
		global $pop_automatedemails_manager;
		$pop_automatedemails_manager->add($this);
	}

    public function get_page() {
        
        return null;
    }
    
    public function get_emails() {
        
        // Emails is an array of arrays, each of which has the following format:
        // $item = array(
        //     'users' => $this->get_users(),
        //     'recipients' => $this->get_recipients(),
        //     'subject' => $this->get_subject(),
        //     'content' => $this->get_content(),
        //     'frame' => $this->get_frame(),
        // );
        return array();
    }
}

