<?php
//this will hold some stuff for updating

//taken from EM_Event onstructor
$event->recurrence_byday = ( empty($event['recurrence_byday']) || $event['recurrence_byday'] == 7 ) ? 0:$event['recurrence_byday']; //Backward compatibility (since 3.0.3), using 0 makes more sense due to date() function
