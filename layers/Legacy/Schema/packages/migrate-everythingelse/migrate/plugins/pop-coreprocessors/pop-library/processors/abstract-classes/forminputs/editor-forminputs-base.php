<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_EditorFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_EDITOR];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        // Allow Mentions to add its required templates (User/Tag Mention Layout)
        if ($layouts = $this->getEditorLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }

    protected function getEditorLayoutSubmodules(array $module)
    {

        // Allow Mentions to add its required templates (User/Tag Mention Layout)
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_Module_Processor_EditorFormInputsBase:editor_layouts',
            array()
        );
    }

    public function getModulesToPropagateDataProperties(array $module): array
    {

        // Important: the MENTION_COMPONENT (eg: PoP_Module_Processor_UserMentionComponentLayouts::MODULE_LAYOUTUSER_MENTION_COMPONENT) should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
        // but it applies to @Mentions, which doesn't need these parameters, however these, here, upset the whole getDatasetmoduletreeSectionFlattenedDataFields
        // To fix this, in the editor data_properties we stop spreading down, so it never reaches below there to get further data-fields
        if ($this->getEditorLayoutSubmodules($module)) {
            // Do nothing
            return array();
        }

        return parent::getModulesToPropagateDataProperties($module);
    }

    public function addQuicktags(array $module, array &$props)
    {
        return false;
    }

    public function getRows(array $module, array &$props)
    {

        // Allow pageSection Addons to define how many rows it will have
        /*if ($rows = $this->get_general_prop($props, 'editor-rows')) {

        return $rows;
        }
        else*/if ($rows = $this->getProp($module, $props, 'editor-rows')) {
            return $rows;
}
        return 0;
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);
        $this->addJsmethod($ret, 'editor');

        // To hide the .wp-media-buttons under the specific (user is not logged in) domain
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($module, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'editor-';

        return $ret;
    }

    public function autofocus(array $module, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $name = $this->getName($module);

        // Allow to add extra classes (eg: "pop-form-clear")
        $class = $this->getProp($module, $props, 'class');
        $quicktags = $this->addQuicktags($module, $props);

        // Generate a random id, needed to be able to load more than 1 wpEditor using Template Manager
        $editor_id = $name.'_'.ComponentModelComponentInfo::get('unique-id');
        $options = array(
            'editor_class' => 'pop-editor ' . $class,
            'textarea_name' => $name,
            'quicktags' => $quicktags,
        );
        if ($rows = $this->getRows($module, $props)) {
            $options['textarea_rows'] = $rows;
        }

        // For the replicate functionality, we need to replace the ComponentModelComponentInfo::get('unique-id') bit from the IDs (generated on html load) with the newly
        // generated unique-id from the feedback
        // In addition, allow others to also add their own replacements. Eg: in forms we can add the dbObject value to edit in the wp-editor
        // This replacement below must be done always
        $ret['unique-id'] = ComponentModelComponentInfo::get('unique-id');

        $initialtext = $this->getInitialtext($module, $props);
        $ret['initial-text'] = $initialtext;
        $ret['editor-code'] = PoP_EditorUtils::getEditorCode($editor_id, $initialtext, $options);

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($this->autofocus($module, $props)) {
            $this->appendProp($module, $props, 'class', 'pop-editor-autofocus');
        }

        parent::initModelProps($module, $props);
    }

    public function getInitialtext(array $module, array &$props)
    {

        // This is needed since this string will be replaced by the actual content (either empty, or the post content, or etc)
        return TranslationAPIFacade::getInstance()->__('Please write the content here...', 'pop-coreprocessors');
    }

    public function getDbobjectField(array $module)
    {
        return 'contentEditor';
    }
}
