<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class Wassup_Module_Processor_FormMultipleComponents extends PoP_Module_Processor_MultiplesBase
{
    public final const MODULE_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH = 'multicomponent-forminputs-moderatedpublish';
    public final const MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH = 'multicomponent-forminputs-unmoderatedpublish';
    public final const MODULE_MULTICOMPONENT_FORM_LEFTSIDE = 'multicomponent-form-leftside';
    public final const MODULE_MULTICOMPONENT_FORM_LINK_LEFTSIDE = 'multicomponent-form-link-left';
    public final const MODULE_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE = 'multicomponent-form-contentpostlink-left';
    public final const MODULE_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE = 'multicomponent-form-contentpostlink-rightside';
    public final const MODULE_MULTICOMPONENT_FORM_POST_LEFTSIDE = 'multicomponent-form-post-leftside';
    public final const MODULE_MULTICOMPONENT_FORM_POST_RIGHTSIDE = 'multicomponent-form-post-rightside';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH],
            [self::class, self::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH],
            [self::class, self::MODULE_MULTICOMPONENT_FORM_LEFTSIDE],
            [self::class, self::MODULE_MULTICOMPONENT_FORM_LINK_LEFTSIDE],
            [self::class, self::MODULE_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE],
            [self::class, self::MODULE_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE],
            [self::class, self::MODULE_MULTICOMPONENT_FORM_POST_LEFTSIDE],
            [self::class, self::MODULE_MULTICOMPONENT_FORM_POST_RIGHTSIDE],
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

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH:
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_CUP_STATUS];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SUBMIT];
                break;

            case self::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH:
                $ret[] = [PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::class, PoP_Module_Processor_CreateUpdatePostCheckboxFormInputs::MODULE_FORMINPUT_CUP_KEEPASDRAFT];
                $ret[] = [PoP_Module_Processor_SubmitButtons::class, PoP_Module_Processor_SubmitButtons::MODULE_SUBMITBUTTON_SUBMIT];
                break;

            case self::MODULE_MULTICOMPONENT_FORM_LEFTSIDE:
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_CUP_TITLE];
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_EDITOR];
                break;

            case self::MODULE_MULTICOMPONENT_FORM_LINK_LEFTSIDE:
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK];
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE];
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormGroups::class, PoP_Module_Processor_CreateUpdatePostFormGroups::MODULE_FORMGROUP_EMBEDPREVIEW];
                $ret[] = [PoP_Module_Processor_ReloadEmbedPreviewConnectors::class, PoP_Module_Processor_ReloadEmbedPreviewConnectors::MODULE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR];
                break;

            case self::MODULE_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE:
                $sections = $this->canInputMultipleCategories() ?
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS] :
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION];
                $ret[] = $sections;
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINK];
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_CONTENTPOSTLINKS_FORMINPUTGROUP_LINKTITLE];
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormGroups::class, PoP_Module_Processor_CreateUpdatePostFormGroups::MODULE_FORMGROUP_EMBEDPREVIEW];
                $ret[] = [PoP_Module_Processor_ReloadEmbedPreviewConnectors::class, PoP_Module_Processor_ReloadEmbedPreviewConnectors::MODULE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR];
                break;

            case self::MODULE_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE:
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_CONTENTPOSTLINKDETAILS];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_FEATUREDIMAGE];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_METAINFORMATION];
                $status = GD_CreateUpdate_Utils::moderate() ? [self::class, self::MODULE_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] : [self::class, self::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                $ret[] = $status;
                break;

            case self::MODULE_MULTICOMPONENT_FORM_POST_LEFTSIDE:
                $sections = $this->canInputMultipleCategories() ?
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTIONS] :
                [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_BUTTONGROUP_POSTSECTION];
                $ret[] = $sections;
                $ret[] = [PoP_Module_Processor_CreateUpdatePostFormInputGroups::class, PoP_Module_Processor_CreateUpdatePostFormInputGroups::MODULE_FORMINPUTGROUP_CUP_TITLE];
                $ret[] = [PoP_Module_Processor_FormInputGroups::class, PoP_Module_Processor_FormInputGroups::MODULE_FORMINPUTGROUP_EDITOR];
                break;

            case self::MODULE_MULTICOMPONENT_FORM_POST_RIGHTSIDE:
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_CONTENTPOSTDETAILS];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_FEATUREDIMAGE];
                $ret[] = [Wassup_Module_Processor_FormWidgets::class, Wassup_Module_Processor_FormWidgets::MODULE_WIDGET_FORM_METAINFORMATION];
                $status = GD_CreateUpdate_Utils::moderate() ? [self::class, self::MODULE_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] : [self::class, self::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                $ret[] = $status;
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($module[1]) {
            case self::MODULE_MULTICOMPONENT_FORM_CONTENTPOSTLINK_RIGHTSIDE:
            case self::MODULE_MULTICOMPONENT_FORM_POST_RIGHTSIDE:
                if (!($classs = $this->getProp($module, $props, 'forminput-publish-class')/*$this->get_general_prop($props, 'forminput-publish-class')*/)) {
                    $classs = 'alert alert-info';
                }
                $status = GD_CreateUpdate_Utils::moderate() ?
                    [self::class, self::MODULE_MULTICOMPONENT_FORMINPUTS_MODERATEDPUBLISH] :
                    [self::class, self::MODULE_MULTICOMPONENT_FORMINPUTS_UNMODERATEDPUBLISH];
                $this->appendProp($status, $props, 'class', $classs);
                break;

            case self::MODULE_MULTICOMPONENT_FORM_LINK_LEFTSIDE:
            case self::MODULE_MULTICOMPONENT_FORM_CONTENTPOSTLINK_LEFTSIDE:
                // Bind the Embed iframe and the input together. When the input value changes, the iframe
                // will update itself with the URL in the input
                $this->setProp([PoP_Module_Processor_ReloadEmbedPreviewConnectors::class, PoP_Module_Processor_ReloadEmbedPreviewConnectors::MODULE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR], $props, 'iframe-module', [PoP_Module_Processor_EmbedPreviewLayouts::class, PoP_Module_Processor_EmbedPreviewLayouts::MODULE_LAYOUT_USERINPUTEMBEDPREVIEW]);
                $this->setProp([PoP_Module_Processor_ReloadEmbedPreviewConnectors::class, PoP_Module_Processor_ReloadEmbedPreviewConnectors::MODULE_LAYOUT_RELOADEMBEDPREVIEWCONNECTOR], $props, 'input-module', [PoP_Module_Processor_CreateUpdatePostTextFormInputs::class, PoP_Module_Processor_CreateUpdatePostTextFormInputs::MODULE_CONTENTPOSTLINKS_FORMINPUT_LINK]);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



