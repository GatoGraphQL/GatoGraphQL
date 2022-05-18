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

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        switch ($componentVariation[1]) {
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

    public function getBtnClass(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return 'btn btn-compact btn-link';
        }

        return parent::getBtnClass($componentVariation);
    }

    public function getFontawesome(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return 'fa-reply';
        }

        return parent::getFontawesome($componentVariation, $props);
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                return TranslationAPIFacade::getInstance()->__('Reply with...', 'pop-coreprocessors').' <span class="caret"></span>';
        }

        return parent::getLabel($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_DROPDOWNBUTTONCONTROL_ADDRELATEDPOST:
                $this->appendProp($componentVariation, $props, 'class', 'pop-addrelatedpost-dropdown');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


