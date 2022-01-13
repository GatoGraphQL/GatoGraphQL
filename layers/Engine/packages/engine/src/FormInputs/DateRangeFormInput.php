<?php

declare(strict_types=1);

namespace PoP\Engine\FormInputs;

use PoP\Root\Facades\Translation\TranslationAPIFacade;

class DateRangeFormInput extends MultipleInputFormInput
{
    public function __construct($params = array())
    {
        parent::__construct($params);

        // Re-implement to re-create the "readable" input from the other inputs
        if (!isset($params['selected']) && $this->selected) {
            $selected = (array) $this->selected;
            // Transform it to the format needed by the DateRange plugin
            if ($selected['from'] && $selected['to']) {
                // Value to send back to the user, with a nice format
                $selected['readable'] = sprintf(
                    TranslationAPIFacade::getInstance()->__('%1$s - %2$s', 'engine'),
                    date('m/d/Y', strtotime($selected['from'])),
                    date('m/d/Y', strtotime($selected['to']))
                );
            }
        }
    }
}
