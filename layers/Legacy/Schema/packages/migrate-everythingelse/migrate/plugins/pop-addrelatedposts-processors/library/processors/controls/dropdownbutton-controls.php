<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST = 'dropdownbuttoncontrol-addrelatedpost';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST,
        );
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                $ret = array_merge(
                    $ret,
                    \PoP\Root\App::applyFilters(
                        'PoP_Module_Processor_DropdownButtonControls:addrelatedpost-dropdown:buttons',
                        array()
                    )
                );
                break;
        }

        return $ret;
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($component);
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return 'fa-reply';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return TranslationAPIFacade::getInstance()->__('Reply with...', 'pop-coreprocessors').' <span class="caret"></span>';
        }

        return parent::getLabel($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                $this->appendProp($component, $props, 'class', 'pop-addrelatedpost-dropdown');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


