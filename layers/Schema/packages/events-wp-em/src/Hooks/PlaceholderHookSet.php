<?php

declare(strict_types=1);

namespace PoPSchema\EventsWPEM\Hooks;

use PoP\Hooks\AbstractHookSet;
use EM_Event;

class PlaceholderHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            'em_event_output_placeholder',
            [$this, 'getEventOutputEventDates'],
            10,
            3
        );
    }

    public function getEventOutputEventDates(?string $attString, EM_Event $event, string $format): ?string
    {
        preg_match_all("/(#@?_?[A-Za-z0-9]+)({([a-zA-Z0-9_,]+)})?/", $format, $placeholders);
        foreach ($placeholders[1] as $key => $result) {
            switch ($result) {
                case '#_EVENTDATESTART':
                case '#_EVENTDATEEND':
                    // Possible Formats required: http://arshaw.com/fullcalendar/docs/event_data/Event_Object/
                    // WP Formats: http://codex.wordpress.org/Formatting_Date_and_Time
                    $date_format = 'c'; // ISO8601

                    if ($result == '#_EVENTDATESTART') {
                        $date = $event->start;
                    } else {
                        $date = $event->end;
                    }
                    $attString = date_i18n($date_format, $date);
                    break;

                case '#_EVENTALLDAY':
                    $attString = $event->event_all_day;
                    break;
            }
        }

        return $attString;
    }
}
