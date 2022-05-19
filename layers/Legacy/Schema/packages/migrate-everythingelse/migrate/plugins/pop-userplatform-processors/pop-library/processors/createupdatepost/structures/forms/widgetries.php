<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Wassup_Module_Processor_FormWidgets extends PoP_Module_Processor_WidgetsBase
{
    public final const COMPONENT_WIDGET_FORM_FEATUREDIMAGE = 'widget-form-featuredimage';
    public final const COMPONENT_WIDGET_FORM_METAINFORMATION = 'widget-form-metainformation';
    public final const COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS = 'widget-form-linkdetails';
    public final const COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS = 'widget-form-postdetails';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGET_FORM_FEATUREDIMAGE],
            [self::class, self::COMPONENT_WIDGET_FORM_METAINFORMATION],
            [self::class, self::COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS],
            [self::class, self::COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS],
        );
    }

    public function getLayoutSubcomponents(array $component)
    {
        $ret = parent::getLayoutSubcomponents($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_FEATUREDIMAGE:
                $ret[] = [GD_ContentCreation_Module_Processor_FormInputGroups::class, GD_ContentCreation_Module_Processor_FormInputGroups::COMPONENT_FORMCOMPONENTGROUP_FEATUREDIMAGE];
                break;

            case self::COMPONENT_WIDGET_FORM_METAINFORMATION:
                $ret[] = [PoP_RelatedPosts_Module_Processor_FormComponentGroups::class, PoP_RelatedPosts_Module_Processor_FormComponentGroups::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES];
                if (defined('POP_COAUTHORSPROCESSORS_INITIALIZED')) {
                    $ret[] = [GD_CAP_Module_Processor_FormComponentGroups::class, GD_CAP_Module_Processor_FormComponentGroups::COMPONENT_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS];
                }
                if (defined('POP_ADDPOSTLINKSPROCESSORS_INITIALIZED')) {
                    $ret[] = [PoP_AddPostLinks_Module_Processor_FormInputGroups::class, PoP_AddPostLinks_Module_Processor_FormInputGroups::COMPONENT_ADDPOSTLINKS_FORMINPUTGROUP_LINK];
                }
                break;

            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
                // $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKCATEGORIES];
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_APPLIESTO];
                }
                if (PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKACCESS];
                }
                break;

            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS:
                if (PoP_ApplicationProcessors_Utils::addCategories()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_CATEGORIES];
                }
                if (PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_APPLIESTO];
                }
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_WIDGET_FORM_FEATUREDIMAGE => TranslationAPIFacade::getInstance()->__('Featured Image', 'poptheme-wassup'),
            self::COMPONENT_WIDGET_FORM_METAINFORMATION => TranslationAPIFacade::getInstance()->__('Meta information', 'poptheme-wassup'),
            self::COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS => TranslationAPIFacade::getInstance()->__('Link details', 'poptheme-wassup'),
            self::COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS => TranslationAPIFacade::getInstance()->__('Post details', 'poptheme-wassup'),
        );

        return $titles[$component[1]] ?? null;
    }

    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_FEATUREDIMAGE:
            case self::COMPONENT_WIDGET_FORM_METAINFORMATION:
            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS:
                if ($class = $this->getProp($component, $props, 'form-widget-class')/*$this->get_general_prop($props, 'form-widget-class')*/) {
                    return $class;
                }

                return 'panel panel-info';
        }

        return parent::getWidgetClass($component, $props);
    }

    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_FEATUREDIMAGE:
            case self::COMPONENT_WIDGET_FORM_METAINFORMATION:
            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS:
                return 'panel-body';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_FEATUREDIMAGE:
            case self::COMPONENT_WIDGET_FORM_METAINFORMATION:
            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS:
                return '';
        }

        return parent::getItemWrapper($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGET_FORM_FEATUREDIMAGE:
                $this->setProp([PoP_Module_Processor_FeaturedImageInnerComponentInputs::class, PoP_Module_Processor_FeaturedImageInnerComponentInputs::COMPONENT_FORMINPUT_FEATUREDIMAGEINNER], $props, 'setbtn-class', 'btn btn-sm btn-link');
                $this->setProp([PoP_Module_Processor_FeaturedImageInnerComponentInputs::class, PoP_Module_Processor_FeaturedImageInnerComponentInputs::COMPONENT_FORMINPUT_FEATUREDIMAGEINNER], $props, 'removebtn-class', 'btn btn-sm btn-link');
                $this->setProp([PoP_Module_Processor_FeaturedImageInnerComponentInputs::class, PoP_Module_Processor_FeaturedImageInnerComponentInputs::COMPONENT_FORMINPUT_FEATUREDIMAGEINNER], $props, 'options-class', '');
                break;

            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS:
                // If the widget has nothing inside, then hide it
                if (!PoP_ApplicationProcessors_Utils::addCategories() && !PoP_ApplicationProcessors_Utils::addAppliesto()) {
                    $this->appendProp($component, $props, 'class', 'hidden');
                }
                break;

            case self::COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS:
                // If the widget has nothing inside, then hide it
                if (!PoP_ApplicationProcessors_Utils::addCategories() && !PoP_ApplicationProcessors_Utils::addAppliesto() && !PoP_ApplicationProcessors_Utils::addLinkAccesstype()) {
                    $this->appendProp($component, $props, 'class', 'hidden');
                }
                break;
        }

        parent::initModelProps($component, $props);
    }
}



