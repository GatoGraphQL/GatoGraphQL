<?php
// Function that returns true if the event has attendees
function gdEventHasAttendees($event)
{
    foreach ($event->get_bookings() as $EM_Booking) {
        if ($EM_Booking->status == 1) {
            return true;
        }
    }

    return false;
}


// Indicates if the event takes place on day $day
function gdEmEventEventOnGivenDay($day, $event)
{
    $event_dates = array();
    $event_start_date = strtotime($event->start_date);
    $event_end_date = mktime(0, 0, 0, $month_post, date('t', $event_start_date), $year_post);
    if ($event_end_date == '') {
        $event_end_date = $event_start_date;
    }
    while ($event_start_date <= $event->end) {
        //Ensure date is within event dates, if so add to eventful days array
        $event_eventful_date = date('Y-m-d', $event_start_date);
        $event_dates[] = $event_eventful_date;
        $event_start_date += (86400); //add a day
    }

    return in_array($day, $event_dates);
}
