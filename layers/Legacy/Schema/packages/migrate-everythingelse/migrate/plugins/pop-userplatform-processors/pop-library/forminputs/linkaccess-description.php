<?php
use PoP\Engine\FormInputs\SelectFormInput;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class GD_FormInput_LinkAccessDescription extends SelectFormInput
{
    /**
     * @return mixed[]
     */
    public function getAllValues(string $label = null): array
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
