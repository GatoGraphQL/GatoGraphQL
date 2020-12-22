<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class GD_FormInput_OrganizationTypes extends \PoP\Engine\GD_FormInput_MultiSelect
{
    public function getAllValues($label = null)
    {
        $values = parent::getAllValues($label);
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();

        $types = array(
            $cmsapplicationhelpers->sanitizeTitle('Charity') => TranslationAPIFacade::getInstance()->__('Charity', 'pop-commonuserroles'),
            $cmsapplicationhelpers->sanitizeTitle('Communities') => TranslationAPIFacade::getInstance()->__('Communities', 'pop-commonuserroles'),
            $cmsapplicationhelpers->sanitizeTitle('Company or Corporate') => TranslationAPIFacade::getInstance()->__('Company or Corporate', 'pop-commonuserroles'),
            $cmsapplicationhelpers->sanitizeTitle('Government') => TranslationAPIFacade::getInstance()->__('Government', 'pop-commonuserroles'),
            $cmsapplicationhelpers->sanitizeTitle('Education') => TranslationAPIFacade::getInstance()->__('Education', 'pop-commonuserroles'),
            $cmsapplicationhelpers->sanitizeTitle('NGO/NPO') => TranslationAPIFacade::getInstance()->__('NGO/NPO', 'pop-commonuserroles'),
            $cmsapplicationhelpers->sanitizeTitle('Social Enterprise') => TranslationAPIFacade::getInstance()->__('Social Enterprise', 'pop-commonuserroles'),
            $cmsapplicationhelpers->sanitizeTitle('Youth') => TranslationAPIFacade::getInstance()->__('Youth', 'pop-commonuserroles'),
        );

        $values = array_merge(
            $values,
            HooksAPIFacade::getInstance()->applyFilters('wassup_organizationtypes', $types)
        );
        
        return $values;
    }
}
