<?php

class PoP_AutomatedEmailsManager
{
    public $automatedemails;
    
    public function __construct()
    {
        $this->automatedemails = array();
    }
    
    // Comment Leo 31/05/2018: do not access $automatedemail->getRoute() during object __construct, because the route constant may not have been defined yet
    // Instead, access it when getting all the automatedemails for that route
    //    function add($automatedemail) {
    
    //     // Each page can have many automatedemails (eg: 1 for users, 1 for recipients from Gravity Forms)
    //        if (!$this->automatedemails[$automatedemail->getRoute()]) {
    //            $this->automatedemails[$automatedemail->getRoute()] = array();
    //        }
    //     $this->automatedemails[$automatedemail->getRoute()][] = $automatedemail;
    // }
    // function getAutomatedemails($page) {

    //     return $this->automatedemails[$page];
    // }

    public function add($automatedemail)
    {
        $this->automatedemails[] = $automatedemail;
    }
    
    public function getAutomatedEmails($route)
    {

        // Each page can have many automatedemails (eg: 1 for users, 1 for recipients from Gravity Forms)
        $page_automatedemails = array();
        foreach ($this->automatedemails as $automatedemail) {
            if ($automatedemail->getRoute() == $route) {
                $page_automatedemails[] = $automatedemail;
            }
        }
        return $page_automatedemails;
    }
}
    
/**
 * Initialize
 */
global $pop_automatedemails_manager;
$pop_automatedemails_manager = new PoP_AutomatedEmailsManager();
