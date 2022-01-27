<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public const MODULE_BUTTON_STANCEEDIT = 'button-stanceedit';
    public const MODULE_BUTTON_STANCEVIEW = 'button-stanceview';
    public const MODULE_BUTTON_POSTSTANCES_PRO = 'button-poststances-pro';
    public const MODULE_BUTTON_POSTSTANCES_NEUTRAL = 'button-poststances-neutral';
    public const MODULE_BUTTON_POSTSTANCES_AGAINST = 'button-poststances-against';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_BUTTON_STANCEEDIT],
            [self::class, self::MODULE_BUTTON_STANCEVIEW],
            [self::class, self::MODULE_BUTTON_POSTSTANCES_PRO],
            [self::class, self::MODULE_BUTTON_POSTSTANCES_NEUTRAL],
            [self::class, self::MODULE_BUTTON_POSTSTANCES_AGAINST],
        );
    }

    public function getButtoninnerSubmodule(array $module)
    {
        $buttoninners = array(
            self::MODULE_BUTTON_STANCEEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTEDIT],
            self::MODULE_BUTTON_STANCEVIEW => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTVIEW],
            self::MODULE_BUTTON_POSTSTANCES_PRO => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTSTANCE_PRO],
            self::MODULE_BUTTON_POSTSTANCES_NEUTRAL => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTSTANCE_NEUTRAL],
            self::MODULE_BUTTON_POSTSTANCES_AGAINST => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::MODULE_BUTTONINNER_POSTSTANCE_AGAINST],
        );
        if ($buttoninner = $buttoninners[$module[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubmodule($module);
    }

    public function getUrlField(array $module)
    {
        $fields = array(
            self::MODULE_BUTTON_STANCEEDIT => 'editURL',
            self::MODULE_BUTTON_POSTSTANCES_PRO => 'postStancesProURL',
            self::MODULE_BUTTON_POSTSTANCES_NEUTRAL => 'postStancesNeutralURL',
            self::MODULE_BUTTON_POSTSTANCES_AGAINST => 'postStancesAgainstURL',
        );
        if ($field = $fields[$module[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($module);
    }

    public function getTitle(array $module, array &$props)
    {
        $titles = array(
            self::MODULE_BUTTON_STANCEEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'pop-userstance-processors'),
            self::MODULE_BUTTON_STANCEVIEW => TranslationAPIFacade::getInstance()->__('View', 'pop-userstance-processors'),
            self::MODULE_BUTTON_POSTSTANCES_PRO => TranslationAPIFacade::getInstance()->__('Pro', 'pop-userstance-processors'),
            self::MODULE_BUTTON_POSTSTANCES_NEUTRAL => TranslationAPIFacade::getInstance()->__('Neutral', 'pop-userstance-processors'),
            self::MODULE_BUTTON_POSTSTANCES_AGAINST => TranslationAPIFacade::getInstance()->__('Against', 'pop-userstance-processors'),
        );
        if ($title = $titles[$module[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($module, $props);
    }

    public function getLinktarget(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_BUTTON_STANCEEDIT:
                return POP_TARGET_ADDONS;

            case self::MODULE_BUTTON_POSTSTANCES_PRO:
            case self::MODULE_BUTTON_POSTSTANCES_NEUTRAL:
            case self::MODULE_BUTTON_POSTSTANCES_AGAINST:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getLinktarget($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        $ret = parent::getBtnClass($module, $props);

        switch ($module[1]) {
            case self::MODULE_BUTTON_STANCEVIEW:
            case self::MODULE_BUTTON_STANCEEDIT:
                $ret .= ' btn btn-xs btn-default';
                break;

            case self::MODULE_BUTTON_POSTSTANCES_PRO:
            case self::MODULE_BUTTON_POSTSTANCES_NEUTRAL:
            case self::MODULE_BUTTON_POSTSTANCES_AGAINST:
                $ret .= ' btn btn-link';
                break;
        }

        return $ret;
    }
}


