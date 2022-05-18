<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class Wassup_Module_Processor_FormMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH = 'multicomponent-forminputs-moderatedpublish';
    public final const COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH = 'multicomponent-forminputs-unmoderatedpublish';
    public final const COMPONENT_MULTICOMPONENT_FORM_LEFTSIDE = 'multicomponent-form-leftside';
    public final const COMPONENT_MULTICOMPONENT_FORM_LINK_LEFTSIDE = 'multicomponent-form-link-left';
    public final const COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE = 'multicomponent-form-contentpostlink-left';
    public final const COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE = 'multicomponent-form-contentpostlink-rightside';
    public final const COMPONENT_MULTICOMPONENT_FORM_POST_LEFTSIDE = 'multicomponent-form-post-leftside';
    public final const COMPONENT_MULTICOMPONENT_FORM_POST_RIGHTSIDE = 'multicomponent-form-post-rightside';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH],
            [self::class, self::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH],
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_LEFTSIDE],
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_LINK_LEFTSIDE],
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE],
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE],
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_POST_LEFTSIDE],
            [self::class, self::COMPONENT_MULTICOMPONENT_FORM_POST_RIGHTSIDE],
        );
    }

    protected function canInputMultipleCategories()
    {
        return false;
        // return \PoP\Root\App::applyFilters(
        //     'GD_CreateUpdate_Post:multiple-categories',
        //     true
        // );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH:
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_CUP_STATUS];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SUBMIT];
                break;

            case self::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH:
                $ret[] = [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::COMPONENT_FORMINPUT_CUP_KEEPASDRAFT];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::COMPONENT_SUBMITBUTTON_SUBMIT];
                break;

            case self::COMPONENT_MULTICOMPONENT_FORM_LEFTSIDE:
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_CUP_TITLE];
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_EDITOR];
                break;

            case self::COMPONENT_MULTICOMPONENT_FORM_LINK_LEFTSIDE:
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK];
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE];
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormGroups::class, PoP_Module_Processor_CreateUpdatePostFormGroups::COMPONENT_FORMGROUP_EMBEDPREVIEW];
                $ret[] = [PoP_Module_Processor_ReloadEmbedPreviewConnectors::class, PoP_Module_Processor_ReloadEmbedPreviewConnectors::COMPONENT_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR];
                break;

            case self::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE:
                $sections = $this->canInputMultipleCategories() ?
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS] :
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION];
                $ret[] = $sections;
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK];
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE];
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormGroups::class, PoP_Module_Processor_CreateUpdatePostFormGroups::COMPONENT_FORMGROUP_EMBEDPREVIEW];
                $ret[] = [PoP_Module_Processor_ReloadEmbedPreviewConnectors::class, PoP_Module_Processor_ReloadEmbedPreviewConnectors::COMPONENT_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR];
                break;

            case self::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE:
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_CONTENTPOSTLINKDETAILS];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_FEATUREDIMAGE];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_METAINFORMATION];
                $status = GD_CreateUpdate_Utils::moderate() ? [self::class, self::COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] : [self::class, self::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                $ret[] = $status;
                break;

            case self::COMPONENT_MULTICOMPONENT_FORM_POST_LEFTSIDE:
                $sections = $this->canInputMultipleCategories() ?
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS] :
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION];
                $ret[] = $sections;
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::COMPONENT_FORMINPUTGROUP_CUP_TITLE];
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::COMPONENT_FORMINPUTGROUP_EDITOR];
                break;

            case self::COMPONENT_MULTICOMPONENT_FORM_POST_RIGHTSIDE:
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_CONTENTPOSTDETAILS];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_FEATUREDIMAGE];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::COMPONENT_WIDGET_FORM_METAINFORMATION];
                $status = GD_CreateUpdate_Utils::moderate() ? [self::class, self::COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] : [self::class, self::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                $ret[] = $status;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE:
            case self::COMPONENT_MULTICOMPONENT_FORM_POST_RIGHTSIDE:
                if (!($classs = $this->getProp($component, $props, 'forminput-publish-class')/*$this->get_general_prop($props, 'forminput-publish-class')*/)) {
                    $classs = 'alert alert-info';
                }
                $status = GD_CreateUpdate_Utils::moderate() ?
                    [self::class, self::COMPONENT_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] :
                    [self::class, self::COMPONENT_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                $this->appendProp($status, $props, 'class', $classs);
                break;

            case self::COMPONENT_MULTICOMPONENT_FORM_LINK_LEFTSIDE:
            case self::COMPONENT_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE:
                // Bind the Embed iframe and the input together. When the input value changes, the iframe
                // will update itself with the URL in the input
                $this->setProp([PoP_Module_Processor_ReloadEmbedPreviewConnectors::class, PoP_Module_Processor_ReloadEmbedPreviewConnectors::COMPONENT_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR], $props, 'iframe-module', [PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::COMPONENT_LAYOUT_USERINPUTEMBEDPREVIEW]);
                $this->setProp([PoP_Module_Processor_ReloadEmbedPreviewConnectors::class, PoP_Module_Processor_ReloadEmbedPreviewConnectors::COMPONENT_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR], $props, 'input-module', [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::COMPONENT_CONTENTPOSTLINKS_FORMINPUT_LINK]);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



