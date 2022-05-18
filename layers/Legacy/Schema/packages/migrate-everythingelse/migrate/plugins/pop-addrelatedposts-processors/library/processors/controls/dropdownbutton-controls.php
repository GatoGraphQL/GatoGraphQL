<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST = 'dropdownbuttoncontrol-addrelatedpost';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST],
        );
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        switch ($component[1]) {
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

    public function getBtnClass(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($component);
    }

    public function getFontawesome(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return 'fa-reply';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return TranslationAPIFacade::getInstance()->__('Reply with...', 'pop-coreprocessors').' <span class="caret"></span>';
        }

        return parent::getLabel($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                $this->appendProp($component, $props, 'class', 'pop-addrelatedpost-dropdown');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


