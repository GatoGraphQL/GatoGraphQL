<?php
namespace PoP\Engine;
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_FormInput_DateRange extends GD_FormInput_MultipleInputs
{
    public function __construct($params = array())
    {
        parent::__construct($params);
        
        // Re-implement to re-create the "readable" input from the other inputs
        if (!isset($params['selected']) && $this->selected) {

            // Transform it to the format needed by the DateRange plugin
            if ($this->selected['from'] && $this->selected['to']) {

                // Value to send back to the user, with a nice format
                $this->selected['readable'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('%1$s%2$s%3$s', 'pop-application'),
                    date('m/d/Y', strtotime($this->selected['from'])),
                    GD_DATERANGE_SEPARATOR,
                    date('m/d/Y', strtotime($this->selected['to']))
                );
            }
        }
    }
}
