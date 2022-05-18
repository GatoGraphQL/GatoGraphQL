<?php
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_EditorFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_EDITOR];
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        // Allow Mentions to add its required templates (User/Tag Mention Layout)
        if ($layouts = $this->getEditorLayoutSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }

    protected function getEditorLayoutSubmodules(array $component)
    {

        // Allow Mentions to add its required templates (User/Tag Mention Layout)
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_EditorFormInputsBase:editor_layouts',
            array()
        );
    }

    public function getModulesToPropagateDataProperties(array $component): array
    {

        // Important: the MENTION_COMPONENT (eg: PoP_Module_Processor_UserMentionComponentLayouts::COMPONENT_LAYOUTUSER_MENTION_COMPONENT) should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
        // but it applies to @Mentions, which doesn't need these parameters, however these, here, upset the whole getDatasetmoduletreeSectionFlattenedDataFields
        // To fix this, in the editor data_properties we stop spreading down, so it never reaches below there to get further data-fields
        if ($this->getEditorLayoutSubmodules($component)) {
            // Do nothing
            return array();
        }

        return parent::getModulesToPropagateDataProperties($component);
    }

    public function addQuicktags(array $component, array &$props)
    {
        return false;
    }

    public function getRows(array $component, array &$props)
    {

        // Allow pageSection Addons to define how many rows it will have
        /*if ($rows = $this->get_general_prop($props, 'editor-rows')) {

        return $rows;
        }
        else*/if ($rows = $this->getProp($component, $props, 'editor-rows')) {
            return $rows;
}
        return 0;
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);
        $this->addJsmethod($ret, 'editor');

        // To hide the .wp-media-buttons under the specific (user is not logged in) domain
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($component, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'editor-';

        return $ret;
    }

    public function autofocus(array $component, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $name = $this->getName($component);

        // Allow to add extra classes (eg: "pop-form-clear")
        $class = $this->getProp($component, $props, 'class');
        $quicktags = $this->addQuicktags($component, $props);

        // Generate a random id, needed to be able to load more than 1 wpEditor using Template Manager
        $editor_id = $name.'_'.ComponentModelModuleInfo::get('unique-id');
        $options = array(
            'editor_class' => 'pop-editor ' . $class,
            'textarea_name' => $name,
            'quicktags' => $quicktags,
        );
        if ($rows = $this->getRows($component, $props)) {
            $options['textarea_rows'] = $rows;
        }

        // For the replicate functionality, we need to replace the ComponentModelModuleInfo::get('unique-id') bit from the IDs (generated on html load) with the newly
        // generated unique-id from the feedback
        // In addition, allow others to also add their own replacements. Eg: in forms we can add the dbObject value to edit in the wp-editor
        // This replacement below must be done always
        $ret['unique-id'] = ComponentModelModuleInfo::get('unique-id');

        $initialtext = $this->getInitialtext($component, $props);
        $ret['initial-text'] = $initialtext;
        $ret['editor-code'] = PoP_EditorUtils::getEditorCode($editor_id, $initialtext, $options);

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($this->autofocus($component, $props)) {
            $this->appendProp($component, $props, 'class', 'pop-editor-autofocus');
        }

        parent::initModelProps($component, $props);
    }

    public function getInitialtext(array $component, array &$props)
    {

        // This is needed since this string will be replaced by the actual content (either empty, or the post content, or etc)
        return TranslationAPIFacade::getInstance()->__('Please write the content here...', 'pop-coreprocessors');
    }

    public function getDbobjectField(array $component): ?string
    {
        return 'contentEditor';
    }
}
