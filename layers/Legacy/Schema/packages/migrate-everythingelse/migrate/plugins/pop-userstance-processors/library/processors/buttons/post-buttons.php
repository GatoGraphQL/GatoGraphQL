<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_PostButtons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const MODULE_BUTTON_STANCE_CREATE = 'postbutton-stance-create';
    public final const MODULE_BUTTON_STANCE_UPDATE = 'postbutton-stance-update';
    public final const MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE = 'lazypostbutton-stance-createorupdate';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_STANCE_CREATE],
            [self::class, self::MODULE_BUTTON_STANCE_UPDATE],
            [self::class, self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE],
        );
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret[] = 'createStanceButtonLazy';
                break;
        }

        return $ret;
    }

    public function getButtoninnerSubmodule(array $componentVariation)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_STANCE_CREATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_BUTTONINNER_STANCE_CREATE],
            self::MODULE_BUTTON_STANCE_UPDATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_BUTTONINNER_STANCE_UPDATE],
            self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_LAZYBUTTONINNER_STANCE_CREATEORUPDATE],
        );
        if ($buttoninner = $buttoninners[$componentVariation[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($componentVariation);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_STANCE_CREATE:
            case self::MODULE_BUTTON_STANCE_UPDATE:
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                return array(
                    [PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_STANCETARGET],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($componentVariation);
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_STANCE_CREATE:
            case self::MODULE_BUTTON_STANCE_UPDATE:
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($componentVariation, $props);
    }

    public function getBtnClass(array $componentVariation, array &$props)
    {
        $ret = parent::getBtnClass($componentVariation, $props);

        switch ($componentVariation[1]) {
            case self::MODULE_BUTTON_STANCE_CREATE:
            case self::MODULE_BUTTON_STANCE_UPDATE:
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret .= ' btn btn-link';
                break;
        }

        switch ($componentVariation[1]) {
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret .= ' disabled';
                break;
        }

        return $ret;
    }

    public function getTitle(array $componentVariation, array &$props)
    {

        // Allow Events to have a different title
        $stance = sprintf(
            TranslationAPIFacade::getInstance()->__('%s, %s', 'pop-userstance-processors'),
            PoP_UserStanceProcessors_Utils::getWhatisyourvoteTitle('lc'),
            UserStance_Module_Processor_ButtonUtils::getFullviewAddstanceTitle()
        );
        $titles = array(
            self::MODULE_BUTTON_STANCE_CREATE => $stance,
            self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE => $stance,
            self::MODULE_BUTTON_STANCE_UPDATE => sprintf(
                TranslationAPIFacade::getInstance()->__('Edit your corresponding %s', 'pop-userstance-processors'),
                PoP_UserStance_PostNameUtils::getNameLc()
            ),
        );
        if ($title = $titles[$componentVariation[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($componentVariation, $props);
    }

    public function getUrlField(array $componentVariation)
    {
        $fields = array(
            self::MODULE_BUTTON_STANCE_CREATE => 'addStanceURL',
            self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE => 'addStanceURL',
            self::MODULE_BUTTON_STANCE_UPDATE => 'editStanceURL',
        );
        if ($field = $fields[$componentVariation[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($componentVariation);
    }
}



