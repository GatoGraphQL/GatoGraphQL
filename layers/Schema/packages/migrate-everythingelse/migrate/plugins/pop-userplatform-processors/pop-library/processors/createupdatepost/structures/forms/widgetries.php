<?php
use PoP\Translation\Facades\TranslationAPIFacade;

class Wassup_Module_Processor_FormWidgets extends PoP_Module_Processor_WidgetsBase
{
    public const MODULE_WIDGET_FORM_FEATUREDIMAGE = 'widget-form-featuredimage';
    public const MODULE_WIDGET_FORM_METAINFORMATION = 'widget-form-metainformation';
    public const MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS = 'widget-form-linkdetails';
    public const MODULE_WIDGET_FORM_CONTENTPOSTDETAILS = 'widget-form-postdetails';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_FORM_FEATUREDIMAGE],
            [self::class, self::MODULE_WIDGET_FORM_METAINFORMATION],
            [self::class, self::MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS],
            [self::class, self::MODULE_WIDGET_FORM_CONTENTPOSTDETAILS],
        );
    }

    public function getLayoutSubmodules(array $module)
    {
        $ret = parent::getLayoutSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_FEATUREDIMAGE:
                $ret[] = [GD_ContentCreation_Module_Processor_FormInputGroups::class, GD_ContentCreation_Module_Processor_FormInputGroups::MODULE_FORMCOMPONENTGROUP_FEATUREDIMAGE];
                break;

            case self::MODULE_WIDGET_FORM_METAINFORMATION:
                $ret[] = [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES];
                if (defined('POP_COAUTHORSPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_CAP_Module_Processor_FormComponentGroups::class, GD_CAP_Module_Processor_FormComponentGroups::MODULE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS];
                }
                if (defined('POP_ADDPOSTLINKSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_AddPostLinks_Module_Processor_FormInputGroups::class, PoP_AddPostLinks_Module_Processor_FormInputGroups::MODULE_ADDPOSTLINKS_FORMINPUTGROUP_LINK];
                }
                break;

            case self::MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
                // $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_APPLIESTO];
                }
                if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS];
                }
                break;

            case self::MODULE_WIDGET_FORM_CONTENTPOSTDETAILS:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_APPLIESTO];
                }
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_WIDGET_FORM_FEATUREDIMAGE => TranslationAPIFacade::getInstance()->__('Featured Image', 'poptheme-wassup'),
            self::MODULE_WIDGET_FORM_METAINFORMATION => TranslationAPIFacade::getInstance()->__('Meta information', 'poptheme-wassup'),
            self::MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS => TranslationAPIFacade::getInstance()->__('Link details', 'poptheme-wassup'),
            self::MODULE_WIDGET_FORM_CONTENTPOSTDETAILS => TranslationAPIFacade::getInstance()->__('Post details', 'poptheme-wassup'),
        );

        return $titles[$module[1]] ?? null;
    }

    public function getWidgetClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_FEATUREDIMAGE:
            case self::MODULE_WIDGET_FORM_METAINFORMATION:
            case self::MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
            case self::MODULE_WIDGET_FORM_CONTENTPOSTDETAILS:
                if ($class = $this->getProp($module, $props, 'form-widget-class')/*$this->get_general_prop($props, 'form-widget-class')*/) {
                    return $class;
                }

                return 'panel panel-info';
        }

        return parent::getWidgetClass($module, $props);
    }

    public function getBodyClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_FEATUREDIMAGE:
            case self::MODULE_WIDGET_FORM_METAINFORMATION:
            case self::MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
            case self::MODULE_WIDGET_FORM_CONTENTPOSTDETAILS:
                return 'panel-body';
        }

        return parent::getBodyClass($module, $props);
    }
    public function getItemWrapper(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_FEATUREDIMAGE:
            case self::MODULE_WIDGET_FORM_METAINFORMATION:
            case self::MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
            case self::MODULE_WIDGET_FORM_CONTENTPOSTDETAILS:
                return '';
        }

        return parent::getItemWrapper($module, $props);
    }

    public function initModelProps(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_WIDGET_FORM_FEATUREDIMAGE:
                $this->setProp([PoP_Module_Processor_FeaturedImageInnerComponentInputs::class, PoP_Module_Processor_FeaturedImageInnerComponentInputs::MODULE_FORMINPUT_FEATUREDIMAGEINNER], $props, 'setbtn-class', 'btn btn-sm btn-link');
                $this->setProp([PoP_Module_Processor_FeaturedImageInnerComponentInputs::class, PoP_Module_Processor_FeaturedImageInnerComponentInputs::MODULE_FORMINPUT_FEATUREDIMAGEINNER], $props, 'removebtn-class', 'btn btn-sm btn-link');
                $this->setProp([PoP_Module_Processor_FeaturedImageInnerComponentInputs::class, PoP_Module_Processor_FeaturedImageInnerComponentInputs::MODULE_FORMINPUT_FEATUREDIMAGEINNER], $props, 'options-class', '');
                break;

            case self::MODULE_WIDGET_FORM_CONTENTPOSTDETAILS:
                // If the widget has nothing inside, then hide it
                if (!PoP_ApplicationProcessors_Utils::addCategories() && !PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $this->appendProp($module, $props, 'class', 'hidden');
                }
                break;

            case self::MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
                // If the widget has nothing inside, then hide it
                if (!PoP_ApplicationProcessors_Utils::addCategories() && !PoP_ApplicationProcessors_Utils::addAppliesto() && !PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
                    $this->appendProp($module, $props, 'class', 'hidden');
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



