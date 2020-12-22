<?php
namespace PoP\Engine;
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_FormInput_DateRangeTime extends GD_FormInput_MultipleInputs
{
    public function __construct($params = array())
    {
        parent::__construct($params);
        
        // Re-implement to re-create the "readable" input from the other inputs
        if (!isset($params['selected']) && $this->selected) {
            $from = $this->selected['from'];
            $fromtime = $this->selected['fromtime'];
            $to = $this->selected['to'];
            $totime = $this->selected['totime'];

            // Transform it to the format needed by the DateRange plugin
            // Please notice that the date formats in daterange(time) are "d/m/Y" and "m/d/Y" or 1 of them will not work for some reason
            if ($from && $fromtime && $to && $totime) {
                $datefrom = $from.' '.$fromtime.':00';
                $dateto = $to.' '.$totime.':00';
                $this->selected['readable'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('%1$s%2$s%3$s', 'pop-application'),
                    date('d/m/Y g:i A', strtotime($datefrom)),
                    GD_DATERANGE_SEPARATOR,
                    date('d/m/Y g:i A', strtotime($dateto))
                );
            }
        }
    }
}
