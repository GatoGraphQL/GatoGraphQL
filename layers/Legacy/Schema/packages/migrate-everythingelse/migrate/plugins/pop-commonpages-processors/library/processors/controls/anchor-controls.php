<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Pages\Facades\PageTypeAPIFacade;

class GD_CommonPages_Module_Processor_CustomAnchorControls extends PoP_Module_Processor_AnchorControlsBase
{
    public final const COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ = 'custombuttoncontrol-addcontentfaq';
    public final const COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ = 'custombuttoncontrol-accountfaq';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ,
            self::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
                return TranslationAPIFacade::getInstance()->__('FAQ: Adding Content', 'poptheme-wassup');

            case self::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                return TranslationAPIFacade::getInstance()->__('FAQ: Registration', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                return 'fa-question';
        }

        return parent::getFontawesome($component, $props);
    }
    public function getHref(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                $pages = array(
                    self::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ => POP_COMMONPAGES_PAGE_ADDCONTENTFAQ,
                    self::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ => POP_COMMONPAGES_PAGE_ACCOUNTFAQ,
                );
                $page_id = $pages[$component->name];
                return $pageTypeAPI->getPermalink($page_id);
        }

        return parent::getHref($component, $props);
    }
    public function getTarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                return POP_TARGET_MODALS;
        }

        return parent::getTarget($component, $props);
    }
    public function getText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                return null;
        }

        return parent::getText($component, $props);
    }
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CUSTOMANCHORCONTROL_ADDCONTENTFAQ:
            case self::COMPONENT_CUSTOMANCHORCONTROL_ACCOUNTFAQ:
                // pop-btn-faq: to hide it for the addons
                $this->appendProp($component, $props, 'class', 'pop-btn-faq btn btn-link btn-compact');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


