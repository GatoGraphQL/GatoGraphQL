<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_FormInput_LinkAccessDescription extends \PoP\Engine\GD_FormInput_Select
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);
        
        $values = array_merge(
            $values,
            array(
                'free' => TranslationAPIFacade::getInstance()->__('Free access', 'poptheme-wassup'),
                'paywall' => TranslationAPIFacade::getInstance()->__('Behind a paywall', 'poptheme-wassup'),
                'walledgarded' => TranslationAPIFacade::getInstance()->__('User account needed', 'poptheme-wassup'),
            )
        );
        
        return $values;
    }
    
    public function getDefaultValue()
    {
        return 'free';
    }
}
