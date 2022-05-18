<?php
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_EditorFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_Forms_TemplateResourceLoaderProcessor::class, PoP_Forms_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_EDITOR];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        // Allow Mentions to add its required templates (User/Tag Mention Layout)
        if ($layouts = $this->getEditorLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $layouts
            );
        }

        return $ret;
    }

    protected function getEditorLayoutSubmodules(array $componentVariation)
    {

        // Allow Mentions to add its required templates (User/Tag Mention Layout)
        return \PoP\Root\App::applyFilters(
            'PoP_Module_Processor_EditorFormInputsBase:editor_layouts',
            array()
        );
    }

    public function getModulesToPropagateDataProperties(array $componentVariation): array
    {

        // Important: the MENTION_COMPONENT (eg: PoP_Module_Processor_UserMentionComponentLayouts::MODULE_LAYOUTUSER_MENTION_COMPONENT) should not have data-fields, because it doesn't apply to {{blockSettings.dataset}}
        // but it applies to @Mentions, which doesn't need these parameters, however these, here, upset the whole getDatasetmoduletreeSectionFlattenedDataFields
        // To fix this, in the editor data_properties we stop spreading down, so it never reaches below there to get further data-fields
        if ($this->getEditorLayoutSubmodules($componentVariation)) {
            // Do nothing
            return array();
        }

        return parent::getModulesToPropagateDataProperties($componentVariation);
    }

    public function addQuicktags(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getRows(array $componentVariation, array &$props)
    {

        // Allow pageSection Addons to define how many rows it will have
        /*if ($rows = $this->get_general_prop($props, 'editor-rows')) {

        return $rows;
        }
        else*/if ($rows = $this->getProp($componentVariation, $props, 'editor-rows')) {
            return $rows;
}
        return 0;
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);
        $this->addJsmethod($ret, 'editor');

        // To hide the .wp-media-buttons under the specific (user is not logged in) domain
        $this->addJsmethod($ret, 'addDomainClass');
        return $ret;
    }

    public function getImmutableJsconfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableJsconfiguration($componentVariation, $props);

        // For function addDomainClass
        $ret['addDomainClass']['prefix'] = 'editor-';

        return $ret;
    }

    public function autofocus(array $componentVariation, array &$props)
    {
        return false;
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $name = $this->getName($componentVariation);

        // Allow to add extra classes (eg: "pop-form-clear")
        $class = $this->getProp($componentVariation, $props, 'class');
        $quicktags = $this->addQuicktags($componentVariation, $props);

        // Generate a random id, needed to be able to load more than 1 wpEditor using Template Manager
        $editor_id = $name.'_'.ComponentModelModuleInfo::get('unique-id');
        $options = array(
            'editor_class' => 'pop-editor ' . $class,
            'textarea_name' => $name,
            'quicktags' => $quicktags,
        );
        if ($rows = $this->getRows($componentVariation, $props)) {
            $options['textarea_rows'] = $rows;
        }

        // For the replicate functionality, we need to replace the ComponentModelModuleInfo::get('unique-id') bit from the IDs (generated on html load) with the newly
        // generated unique-id from the feedback
        // In addition, allow others to also add their own replacements. Eg: in forms we can add the dbObject value to edit in the wp-editor
        // This replacement below must be done always
        $ret['unique-id'] = ComponentModelModuleInfo::get('unique-id');

        $initialtext = $this->getInitialtext($componentVariation, $props);
        $ret['initial-text'] = $initialtext;
        $ret['editor-code'] = PoP_EditorUtils::getEditorCode($editor_id, $initialtext, $options);

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($this->autofocus($componentVariation, $props)) {
            $this->appendProp($componentVariation, $props, 'class', 'pop-editor-autofocus');
        }

        parent::initModelProps($componentVariation, $props);
    }

    public function getInitialtext(array $componentVariation, array &$props)
    {

        // This is needed since this string will be replaced by the actual content (either empty, or the post content, or etc)
        return TranslationAPIFacade::getInstance()->__('Please write the content here...', 'pop-coreprocessors');
    }

    public function getDbobjectField(array $componentVariation): ?string
    {
        return 'contentEditor';
    }
}
