<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\CustomPosts\Types\Status;

class PoP_HTMLCSSPlatform_ConfigurationUtils
{
    public static function getMultilayoutLabels()
    {
        return HooksAPIFacade::getInstance()->applyFilters('pop_modulemanager:multilayout_labels', array());
    }

    public static function getOndateString()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'pop_modulemanager:ondate',
            TranslationAPIFacade::getInstance()->__('<small>on</small> %s', 'pop-engine-htmlcssplatform')
        );
    }

    public static function getStatusSettings()
    {
        $status = array(
            'class' => array(
                Status::DRAFT => 'label-info',
                Status::PENDING => 'label-warning',
                Status::PUBLISHED => 'label-success'
            ),
            'text' => array(
                Status::DRAFT => TranslationAPIFacade::getInstance()->__('Draft', 'pop-engine-htmlcssplatform'),
                Status::PENDING => TranslationAPIFacade::getInstance()->__('Pending to be published', 'pop-engine-htmlcssplatform'),
                Status::PUBLISHED => TranslationAPIFacade::getInstance()->__('Published', 'pop-engine-htmlcssplatform')
            )
        );
        // Allow to override: allow URE to add its Member Status
        return HooksAPIFacade::getInstance()->applyFilters('pop_modulemanager:status_settings', $status);
    }

    public static function getLabelizeClasses()
    {
        $labelize_classes = array(
            TranslationAPIFacade::getInstance()->__('(None)', 'pop-engine-htmlcssplatform') => 'label-none',
        );
        return HooksAPIFacade::getInstance()->applyFilters('pop_modulemanager:labelize_classes', $labelize_classes);
    }

    public static function registerScriptsAndStylesDuringInit()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_HTMLCSSPlatform_ConfigurationUtils:registerScriptsAndStylesDuringInit',
            true
        );
    }
}
