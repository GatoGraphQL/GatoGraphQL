<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostFormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public const MODULE_FORMINPUTGROUP_CUP_TITLE = 'forminputgroup-cup-title';
    public const MODULE_FORMINPUTGROUP_CUP_STATUS = 'forminputgroup-cup-status';
    public const MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK = 'forminputgroup-link';
    public const MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE = 'forminputgroup-linktitle';
    public const MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS = 'forminputgroup-linkaccess';
    public const MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES = 'forminputgroup-linkcategories';
    public const MODULE_FORMINPUTGROUP_APPLIESTO = 'forminputgroup-appliesto';
    public const MODULE_FORMINPUTGROUP_CATEGORIES = 'forminputgroup-categories';
    public const MODULE_FORMINPUTGROUP_HIGHLIGHTEDITOR = 'forminput-highlighteditorgroup';
    public const MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION = 'forminputgroup-buttongroup-postsection';
    public const MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS = 'forminputgroup-buttongroup-postsections';
    public const MODULE_FILTERINPUTGROUP_LINKACCESS = 'filterinputgroup-linkaccess';
    public const MODULE_FILTERINPUTGROUP_LINKCATEGORIES = 'filterinputgroup-linkcategories';
    public const MODULE_FILTERINPUTGROUP_APPLIESTO = 'filterinputgroup-appliesto';
    public const MODULE_FILTERINPUTGROUP_CATEGORIES = 'filterinputgroup-categories';
    public const MODULE_FILTERINPUTGROUP_CONTENTSECTIONS = 'filterinputgroup-contentsections';
    public const MODULE_FILTERINPUTGROUP_POSTSECTIONS = 'filterinputgroup-postsections';
    public const MODULE_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES = 'filterinputgroup-categories-btngroup';
    public const MODULE_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS = 'filterinputgroup-contentsections-btngroup';
    public const MODULE_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS = 'filterinputgroup-postsections-btngroup';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_TITLE],
            [self::class, self::MODULE_FORMINPUTGROUP_CUP_STATUS],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS],
            [self::class, self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES],
            [self::class, self::MODULE_FORMINPUTGROUP_APPLIESTO],
            [self::class, self::MODULE_FORMINPUTGROUP_CATEGORIES],
            [self::class, self::MODULE_FORMINPUTGROUP_HIGHLIGHTEDITOR],
            [self::class, self::MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION],
            [self::class, self::MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS],
            [self::class, self::MODULE_FILTERINPUTGROUP_LINKCATEGORIES],
            [self::class, self::MODULE_FILTERINPUTGROUP_APPLIESTO],
            [self::class, self::MODULE_FILTERINPUTGROUP_CATEGORIES],
            [self::class, self::MODULE_FILTERINPUTGROUP_CONTENTSECTIONS],
            [self::class, self::MODULE_FILTERINPUTGROUP_POSTSECTIONS],
            [self::class, self::MODULE_FILTERINPUTGROUP_LINKACCESS],
            [self::class, self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES],
            [self::class, self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS],
            [self::class, self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS],
        );
    }


    public function getLabelClass(array $module)
    {
        $ret = parent::getLabelClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_LINKCATEGORIES:
            case self::MODULE_FILTERINPUTGROUP_APPLIESTO:
            case self::MODULE_FILTERINPUTGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUTGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUTGROUP_POSTSECTIONS:
            case self::MODULE_FILTERINPUTGROUP_LINKACCESS:
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $module)
    {
        $ret = parent::getFormcontrolClass($module);

        switch ($module[1]) {
            case self::MODULE_FILTERINPUTGROUP_LINKCATEGORIES:
            case self::MODULE_FILTERINPUTGROUP_APPLIESTO:
            case self::MODULE_FILTERINPUTGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUTGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUTGROUP_POSTSECTIONS:
            case self::MODULE_FILTERINPUTGROUP_LINKACCESS:
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CUP_TITLE:
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE:
                return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_FORMINPUT_CUP_TITLE];

            case self::MODULE_FORMINPUTGROUP_CUP_STATUS:
                return [PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::MODULE_FORMINPUT_CUP_STATUS];

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK:
                return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK];

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS:
                return [PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS];

            case self::MODULE_FILTERINPUTGROUP_LINKACCESS:
                return [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_LINKACCESS];

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES:
                return [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES];

            case self::MODULE_FORMINPUTGROUP_APPLIESTO:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_APPLIESTO];

            case self::MODULE_FORMINPUTGROUP_CATEGORIES:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::MODULE_FORMINPUT_CATEGORIES];

            case self::MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTION];

            case self::MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::MODULE_FORMINPUT_BUTTONGROUP_POSTSECTIONS];

            case self::MODULE_FILTERINPUTGROUP_LINKCATEGORIES:
                return [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_LINKCATEGORIES];

            case self::MODULE_FILTERINPUTGROUP_APPLIESTO:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_APPLIESTO];

            case self::MODULE_FILTERINPUTGROUP_CATEGORIES:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES];

            case self::MODULE_FILTERINPUTGROUP_CONTENTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CONTENTSECTIONS];

            case self::MODULE_FILTERINPUTGROUP_POSTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_POSTSECTIONS];

            case self::MODULE_FORMINPUTGROUP_HIGHLIGHTEDITOR:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::MODULE_FORMINPUT_TEXTAREAEDITOR];

            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES];

            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS];

            case self::MODULE_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS];
        }

        return parent::getComponentSubmodule($module);
    }

    public function getInfo(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_CUP_STATUS:
                return TranslationAPIFacade::getInstance()->__('\'Draft\': still editing, our website admin will not publish it yet. \'Finished editing\': our website admin will publish it (almost) immediately. \'Already published\': our website admin has published it, post is already online.', 'poptheme-wassup');

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK:
                return TranslationAPIFacade::getInstance()->__('The URL from any webpage. (Not all websites can be embedded: Facebook, Dropbox and others do not permit it, and browsers do not embed websites on HTTP for security reasons.)', 'poptheme-wassup');

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE:
                return TranslationAPIFacade::getInstance()->__('Please copy/paste here the title from the original article.', 'poptheme-wassup');

            case self::MODULE_FORMINPUTGROUP_HIGHLIGHTEDITOR:
                return TranslationAPIFacade::getInstance()->__('Please copy/paste any important content from the original post.', 'poptheme-wassup');
        }

        return parent::getInfo($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $component = $this->getComponentSubmodule($module);
        switch ($module[1]) {
            case self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK:
                $this->setProp($component, $props, 'placeholder', 'https://...');
                break;

         // case self::MODULE_FORMINPUTGROUP_HIGHLIGHTEDITOR:

         //     $this->setProp($component, $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Copy/paste here...', 'poptheme-wassup'));
         //     break;

            case self::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES:
            case self::MODULE_FORMINPUTGROUP_CATEGORIES:
                $this->setProp($component, $props, 'label', TranslationAPIFacade::getInstance()->__('Select categories', 'poptheme-wassup'));
                break;

            case self::MODULE_FORMINPUTGROUP_APPLIESTO:
                // Allow to override by whoever is establishing the "applies to" values. Eg: "Select countries"
                $label = HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Module_Processor_CreateUpdatePostFormInputGroups:appliesto:label',
                    TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup')
                );
                $this->setProp($component, $props, 'label', $label);
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUTGROUP_HIGHLIGHTEDITOR:
                return TranslationAPIFacade::getInstance()->__('Highlight:', 'poptheme-wassup');
        }

        return parent::getLabel($module, $props);
    }
}



