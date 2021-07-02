<?php

class PoP_AutomatedEmailsBase
{
    public function __construct()
    {
        global $pop_automatedemails_manager;
        $pop_automatedemails_manager->add($this);
    }

    public function getRoute()
    {
        return null;
    }
    
    public function getEmails()
    {
        
        // Emails is an array of arrays, each of which has the following format:
        // $item = array(
        //     'users' => $this->getUsers(),
        //     'recipients' => $this->getRecipients(),
        //     'subject' => $this->getSubject(),
        //     'content' => $this->getContent(),
        //     'frame' => $this->getFrame(),
        // );
        return array();
    }
}
