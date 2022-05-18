<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Pages\Facades\PageTypeAPIFacade;

class GD_CommonPages_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ = 'custombuttoncontrol-addcontentfaq';
    public final const MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ = 'custombuttoncontrol-accountfaq';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ],
            [self::class, self::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
                return TranslationAPIFacade::getInstance()->__('FAQ: Adding Content', 'poptheme-wassup');

            case self::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                return TranslationAPIFacade::getInstance()->__('FAQ: Registration', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                return 'fa-question';
        }

        return parent::getFontawesome($module, $props);
    }
    public function getHref(array $module, array &$props)
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                $pages = array(
                    self::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ => POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
                    self::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ => POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
                );
                $page_id = $pages[$module[1]];
                return $pageTypeAPI->getPermalink($page_id);
        }

        return parent::getHref($module, $props);
    }
    public function getTarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($module, $props);
    }
    public function getText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                return null;
        }

        return parent::getText($module, $props);
    }
    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::MODULE_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                // pop-btn-faq: to hide it for the addons
                $this->appendProp($module, $props, 'class', 'pop-btn-faq btn btn-link btn-compact');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


