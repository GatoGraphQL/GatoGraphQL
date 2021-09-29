<?php
use PoP\Engine\FormInputs\MultipleSelectFormInput;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;

class GD_FormInput_ContactUs_Topics extends MultipleSelectFormInput
{
    public function getAllValues($label = null): array
    {
        $values = parent::getAllValues($label);
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();

        $topics = HooksAPIFacade::getInstance()->applyFilters(
            'gd_gf_contactus_topics',
            array(
                TranslationAPIFacade::getInstance()->__('General', 'pop-genericforms'),
                TranslationAPIFacade::getInstance()->__('Website', 'pop-genericforms'),
                TranslationAPIFacade::getInstance()->__('Others', 'pop-genericforms'),
            )
        );
        foreach ($topics as $topic) {
            $values[$cmsapplicationhelpers->sanitizeTitle($topic)] = $topic;
        }

        return $values;
    }
}
