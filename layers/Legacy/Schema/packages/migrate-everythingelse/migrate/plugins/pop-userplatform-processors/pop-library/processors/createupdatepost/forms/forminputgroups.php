<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CreateUpdatePostFormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_FORMINPUTGROUP_CUP_TITLE = 'forminputgroup-cup-title';
    public final const COMPONENT_FORMINPUTGROUP_CUP_STATUS = 'forminputgroup-cup-status';
    public final const COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK = 'forminputgroup-link';
    public final const COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE = 'forminputgroup-linktitle';
    public final const COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS = 'forminputgroup-linkaccess';
    public final const COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES = 'forminputgroup-linkcategories';
    public final const COMPONENT_FORMINPUTGROUP_APPLIESTO = 'forminputgroup-appliesto';
    public final const COMPONENT_FORMINPUTGROUP_CATEGORIES = 'forminputgroup-categories';
    public final const COMPONENT_FORMINPUTGROUP_HIGHLIGHTEDITOR = 'forminput-highlighteditorgroup';
    public final const COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION = 'forminputgroup-buttongroup-postsection';
    public final const COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS = 'forminputgroup-buttongroup-postsections';
    public final const COMPONENT_FILTERINPUTGROUP_LINKACCESS = 'filterinputgroup-linkaccess';
    public final const COMPONENT_FILTERINPUTGROUP_LINKCATEGORIES = 'filterinputgroup-linkcategories';
    public final const COMPONENT_FILTERINPUTGROUP_APPLIESTO = 'filterinputgroup-appliesto';
    public final const COMPONENT_FILTERINPUTGROUP_CATEGORIES = 'filterinputgroup-categories';
    public final const COMPONENT_FILTERINPUTGROUP_CONTENTSECTIONS = 'filterinputgroup-contentsections';
    public final const COMPONENT_FILTERINPUTGROUP_POSTSECTIONS = 'filterinputgroup-postsections';
    public final const COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES = 'filterinputgroup-categories-btngroup';
    public final const COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS = 'filterinputgroup-contentsections-btngroup';
    public final const COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS = 'filterinputgroup-postsections-btngroup';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUP_TITLE],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CUP_STATUS],
            [self::class, self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK],
            [self::class, self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE],
            [self::class, self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS],
            [self::class, self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES],
            [self::class, self::COMPONENT_FORMINPUTGROUP_APPLIESTO],
            [self::class, self::COMPONENT_FORMINPUTGROUP_CATEGORIES],
            [self::class, self::COMPONENT_FORMINPUTGROUP_HIGHLIGHTEDITOR],
            [self::class, self::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION],
            [self::class, self::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_LINKCATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_APPLIESTO],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_CATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_CONTENTSECTIONS],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_POSTSECTIONS],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_LINKACCESS],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS],
            [self::class, self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS],
        );
    }


    public function getLabelClass(array $component)
    {
        $ret = parent::getLabelClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_LINKCATEGORIES:
            case self::COMPONENT_FILTERINPUTGROUP_APPLIESTO:
            case self::COMPONENT_FILTERINPUTGROUP_CATEGORIES:
            case self::COMPONENT_FILTERINPUTGROUP_CONTENTSECTIONS:
            case self::COMPONENT_FILTERINPUTGROUP_POSTSECTIONS:
            case self::COMPONENT_FILTERINPUTGROUP_LINKACCESS:
            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES:
            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS:
            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS:
                $ret .= ' col-sm-2';
                break;
        }

        return $ret;
    }
    public function getFormcontrolClass(array $component)
    {
        $ret = parent::getFormcontrolClass($component);

        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUTGROUP_LINKCATEGORIES:
            case self::COMPONENT_FILTERINPUTGROUP_APPLIESTO:
            case self::COMPONENT_FILTERINPUTGROUP_CATEGORIES:
            case self::COMPONENT_FILTERINPUTGROUP_CONTENTSECTIONS:
            case self::COMPONENT_FILTERINPUTGROUP_POSTSECTIONS:
            case self::COMPONENT_FILTERINPUTGROUP_LINKACCESS:
            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES:
            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS:
            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS:
                $ret .= ' col-sm-10';
                break;
        }

        return $ret;
    }

    public function getComponentSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_CUP_TITLE:
            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE:
                return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_FORMINPUT_CUP_TITLE];

            case self::COMPONENT_FORMINPUTGROUP_CUP_STATUS:
                return [PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_FORMINPUT_CUP_STATUS];

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK:
                return [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK];

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS:
                return [PoP_Module_Processor_CreateUpdatePostSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostSelectFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKACCESS];

            case self::COMPONENT_FILTERINPUTGROUP_LINKACCESS:
                return [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_LINKACCESS];

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES:
                return [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINKCATEGORIES];

            case self::COMPONENT_FORMINPUTGROUP_APPLIESTO:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_APPLIESTO];

            case self::COMPONENT_FORMINPUTGROUP_CATEGORIES:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFormInputs::COMPONENT_FORMINPUT_CATEGORIES];

            case self::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTION];

            case self::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFormInputs::COMPONENT_FORMINPUT_BUTTONGROUP_POSTSECTIONS];

            case self::COMPONENT_FILTERINPUTGROUP_LINKCATEGORIES:
                return [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_LINKCATEGORIES];

            case self::COMPONENT_FILTERINPUTGROUP_APPLIESTO:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_APPLIESTO];

            case self::COMPONENT_FILTERINPUTGROUP_CATEGORIES:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_CATEGORIES];

            case self::COMPONENT_FILTERINPUTGROUP_CONTENTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_CONTENTSECTIONS];

            case self::COMPONENT_FILTERINPUTGROUP_POSTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_POSTSECTIONS];

            case self::COMPONENT_FORMINPUTGROUP_HIGHLIGHTEDITOR:
                return [PoP_Module_Processor_TextareaFormInputs::class, PoP_Module_Processor_TextareaFormInputs::COMPONENT_FORMINPUT_TEXTAREAEDITOR];

            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CATEGORIES:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES];

            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_CONTENTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS];

            case self::COMPONENT_FILTERINPUTGROUP_BUTTONGROUP_POSTSECTIONS:
                return [PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::class, PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS];
        }

        return parent::getComponentSubcomponent($component);
    }

    public function getInfo(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_CUP_STATUS:
                return TranslationAPIFacade::getInstance()->__('\'Draft\': still editing, our website admin will not publish it yet. \'Finished editing\': our website admin will publish it (almost) immediately. \'Already published\': our website admin has published it, post is already online.', 'poptheme-wassup');

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK:
                return TranslationAPIFacade::getInstance()->__('The URL from any webpage. (Not all websites can be embedded: Facebook, Dropbox and others do not permit it, and browsers do not embed websites on HTTP for security reasons.)', 'poptheme-wassup');

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE:
                return TranslationAPIFacade::getInstance()->__('Please copy/paste here the title from the original article.', 'poptheme-wassup');

            case self::COMPONENT_FORMINPUTGROUP_HIGHLIGHTEDITOR:
                return TranslationAPIFacade::getInstance()->__('Please copy/paste any important content from the original post.', 'poptheme-wassup');
        }

        return parent::getInfo($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        $component = $this->getComponentSubcomponent($component);
        switch ($component[1]) {
            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK:
                $this->setProp($component, $props, 'placeholder', 'https://...');
                break;

         // case self::COMPONENT_FORMINPUTGROUP_HIGHLIGHTEDITOR:

         //     $this->setProp($component, $props, 'placeholder', TranslationAPIFacade::getInstance()->__('Copy/paste here...', 'poptheme-wassup'));
         //     break;

            case self::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES:
            case self::COMPONENT_FORMINPUTGROUP_CATEGORIES:
                $this->setProp($component, $props, 'label', TranslationAPIFacade::getInstance()->__('Select categories', 'poptheme-wassup'));
                break;

            case self::COMPONENT_FORMINPUTGROUP_APPLIESTO:
                // Allow to override by whoever is establishing the "applies to" values. Eg: "Select countries"
                $label = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_CreateUpdatePostFormInputGroups:appliesto:label',
                    TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup')
                );
                $this->setProp($component, $props, 'label', $label);
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FORMINPUTGROUP_HIGHLIGHTEDITOR:
                return TranslationAPIFacade::getInstance()->__('Highlight:', 'poptheme-wassup');
        }

        return parent::getLabel($component, $props);
    }
}



