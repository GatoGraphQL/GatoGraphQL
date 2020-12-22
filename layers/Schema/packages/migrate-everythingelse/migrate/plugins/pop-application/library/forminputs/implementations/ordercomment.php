<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_FormInput_OrderComment extends \PoP\Engine\GD_FormInput_Order
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);
        
        $values = array_merge(
            $values,
            array(
                'date|DESC' => TranslationAPIFacade::getInstance()->__('Latest added', 'pop-application'),
                'date|ASC' => TranslationAPIFacade::getInstance()->__('Earliest added', 'pop-application'),
            )
        );
        
        return $values;
    }
}
