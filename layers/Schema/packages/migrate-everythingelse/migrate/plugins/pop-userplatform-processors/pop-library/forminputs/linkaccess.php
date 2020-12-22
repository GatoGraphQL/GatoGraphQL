<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_FormInput_LinkAccess extends \PoP\Engine\GD_FormInput_MultiSelect
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);
        
        $values = array_merge(
            $values,
            array(
                'free' => TranslationAPIFacade::getInstance()->__('Free access', 'poptheme-wassup'),
                'paywall' => TranslationAPIFacade::getInstance()->__('Behind a paywall', 'poptheme-wassup'),
                'walledgarded' => TranslationAPIFacade::getInstance()->__('User account needed', 'poptheme-wassup')
            )
        );
        
        return $values;
    }
}
