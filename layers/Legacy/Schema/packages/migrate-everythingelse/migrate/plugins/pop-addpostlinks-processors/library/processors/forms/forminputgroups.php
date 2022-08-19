<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddPostLinks_Module_Processor_FormInputGroups extends PoP_Module_Processor_FormComponentGroupsBase
{
    public final const COMPONENT_ADDPOSTLINKS_FORMINPUTGROUP_LINK = 'forminputgroup-postlink';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_ADDPOSTLINKS_FORMINPUTGROUP_LINK,
        );
    }

    public function getComponentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_ADDPOSTLINKS_FORMINPUTGROUP_LINK:
                return [PoP_AddPostLinks_Module_Processor_TextFormInputs::class, PoP_AddPostLinks_Module_Processor_TextFormInputs::COMPONENT_ADDPOSTLINKS_FORMINPUT_LINK];
        }

        return parent::getComponentSubcomponent($component);
    }

    public function getInfo(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_ADDPOSTLINKS_FORMINPUTGROUP_LINK:
                return TranslationAPIFacade::getInstance()->__('The URL from an external webpage, directly referenced by this post.', 'poptheme-wassup');
        }

        return parent::getInfo($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_ADDPOSTLINKS_FORMINPUTGROUP_LINK:
                $component = $this->getComponentSubcomponent($component);
                $this->setProp($component, $props, 'placeholder', 'https://...');
                break;
        }

        parent::initModelProps($component, $props);
    }
}



