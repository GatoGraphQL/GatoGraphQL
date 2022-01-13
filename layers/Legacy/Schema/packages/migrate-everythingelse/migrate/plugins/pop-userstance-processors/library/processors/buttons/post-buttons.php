<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_PostButtons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public const MODULE_BUTTON_STANCE_CREATE = 'postbutton-stance-create';
    public const MODULE_BUTTON_STANCE_UPDATE = 'postbutton-stance-update';
    public const MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE = 'lazypostbutton-stance-createorupdate';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_STANCE_CREATE],
            [self::class, self::MODULE_BUTTON_STANCE_UPDATE],
            [self::class, self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE],
        );
    }

    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        switch ($module[1]) {
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret[] = 'createStanceButtonLazy';
                break;
        }

        return $ret;
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_STANCE_CREATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_BUTTONINNER_STANCE_CREATE],
            self::MODULE_BUTTON_STANCE_UPDATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_BUTTONINNER_STANCE_UPDATE],
            self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_LAZYBUTTONINNER_STANCE_CREATEORUPDATE],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getTargetDynamicallyRenderedSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_STANCE_CREATE:
            case self::MODULE_BUTTON_STANCE_UPDATE:
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                return array(
                    [PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_STANCETARGET],
                );
        }

        return parent::getTargetDynamicallyRenderedSubmodules($module);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_STANCE_CREATE:
            case self::MODULE_BUTTON_STANCE_UPDATE:
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_BUTTON_STANCE_CREATE:
            case self::MODULE_BUTTON_STANCE_UPDATE:
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret .= ' btn btn-link';
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret .= ' disabled';
                break;
        }

        return $ret;
    }

    public function getTitle(array $module, array &$props)
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
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($module, $props);
    }

    public function getUrlField(array $module)
    {
        $fields = array(
            self::MODULE_BUTTON_STANCE_CREATE => 'addStanceURL',
            self::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE => 'addStanceURL',
            self::MODULE_BUTTON_STANCE_UPDATE => 'editStanceURL',
        );
        if ($field = $fields[$module[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($module);
    }
}



