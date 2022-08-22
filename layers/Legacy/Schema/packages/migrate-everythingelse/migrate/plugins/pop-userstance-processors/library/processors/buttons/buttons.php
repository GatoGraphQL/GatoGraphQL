<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_Buttons extends PoP_Module_Processor_ButtonsBase
{
    public final const COMPONENT_BUTTON_STANCEEDIT = 'button-stanceedit';
    public final const COMPONENT_BUTTON_STANCEVIEW = 'button-stanceview';
    public final const COMPONENT_BUTTON_POSTSTANCES_PRO = 'button-poststances-pro';
    public final const COMPONENT_BUTTON_POSTSTANCES_NEUTRAL = 'button-poststances-neutral';
    public final const COMPONENT_BUTTON_POSTSTANCES_AGAINST = 'button-poststances-against';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_BUTTON_STANCEEDIT,
            self::COMPONENT_BUTTON_STANCEVIEW,
            self::COMPONENT_BUTTON_POSTSTANCES_PRO,
            self::COMPONENT_BUTTON_POSTSTANCES_NEUTRAL,
            self::COMPONENT_BUTTON_POSTSTANCES_AGAINST,
        );
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_STANCEEDIT => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTEDIT],
            self::COMPONENT_BUTTON_STANCEVIEW => [PoP_ContentCreation_Module_Processor_ButtonInners::class, PoP_ContentCreation_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTVIEW],
            self::COMPONENT_BUTTON_POSTSTANCES_PRO => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTSTANCE_PRO],
            self::COMPONENT_BUTTON_POSTSTANCES_NEUTRAL => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTSTANCE_NEUTRAL],
            self::COMPONENT_BUTTON_POSTSTANCES_AGAINST => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_POSTSTANCE_AGAINST],
        );
        if ($buttoninner = $buttoninners[$component->name] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        $fields = array(
            self::COMPONENT_BUTTON_STANCEEDIT => 'editURL',
            self::COMPONENT_BUTTON_POSTSTANCES_PRO => 'postStancesProURL',
            self::COMPONENT_BUTTON_POSTSTANCES_NEUTRAL => 'postStancesNeutralURL',
            self::COMPONENT_BUTTON_POSTSTANCES_AGAINST => 'postStancesAgainstURL',
        );
        if ($field = $fields[$component->name] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $titles = array(
            self::COMPONENT_BUTTON_STANCEEDIT => TranslationAPIFacade::getInstance()->__('Edit', 'pop-userstance-processors'),
            self::COMPONENT_BUTTON_STANCEVIEW => TranslationAPIFacade::getInstance()->__('View', 'pop-userstance-processors'),
            self::COMPONENT_BUTTON_POSTSTANCES_PRO => TranslationAPIFacade::getInstance()->__('Pro', 'pop-userstance-processors'),
            self::COMPONENT_BUTTON_POSTSTANCES_NEUTRAL => TranslationAPIFacade::getInstance()->__('Neutral', 'pop-userstance-processors'),
            self::COMPONENT_BUTTON_POSTSTANCES_AGAINST => TranslationAPIFacade::getInstance()->__('Against', 'pop-userstance-processors'),
        );
        if ($title = $titles[$component->name] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_BUTTON_STANCEEDIT:
                return POP_TARGET_ADDONS;

            case self::COMPONENT_BUTTON_POSTSTANCES_PRO:
            case self::COMPONENT_BUTTON_POSTSTANCES_NEUTRAL:
            case self::COMPONENT_BUTTON_POSTSTANCES_AGAINST:
                return POP_TARGET_QUICKVIEW;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component->name) {
            case self::COMPONENT_BUTTON_STANCEVIEW:
            case self::COMPONENT_BUTTON_STANCEEDIT:
                $ret .= ' btn btn-xs btn-default';
                break;

            case self::COMPONENT_BUTTON_POSTSTANCES_PRO:
            case self::COMPONENT_BUTTON_POSTSTANCES_NEUTRAL:
            case self::COMPONENT_BUTTON_POSTSTANCES_AGAINST:
                $ret .= ' btn btn-link';
                break;
        }

        return $ret;
    }
}


