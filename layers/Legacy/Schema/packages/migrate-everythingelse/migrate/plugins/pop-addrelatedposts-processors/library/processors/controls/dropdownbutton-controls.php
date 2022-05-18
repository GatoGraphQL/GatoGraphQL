<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_AddRelatedPosts_Module_Processor_DropdownButtonControls extends PoP_Module_Processor_DropdownButtonControlsBase
{
    public final const MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST = 'dropdownbuttoncontrol-addrelatedpost';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST],
        );
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
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

    public function getBtnClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($module);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return 'fa-reply';
        }

        return parent::getFontawesome($module, $props);
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return TranslationAPIFacade::getInstance()->__('Reply with...', 'pop-coreprocessors').' <span class="caret"></span>';
        }

        return parent::getLabel($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                $this->appendProp($module, $props, 'class', 'pop-addrelatedpost-dropdown');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


