<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Engine\FormInputs\SelectFormInput;

class GD_FormInput_LinkAccessDescription extends SelectFormInput
{
    public function getAllValues($label = null): array
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

    public function getDefaultValue(): mixed
    {
        return 'free';
    }
}
