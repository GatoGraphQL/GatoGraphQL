<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_PostButtons extends PoP_Module_Processor_PreloadTargetDataButtonsBase
{
    public final const COMPONENT_BUTTON_STANCE_CREATE = 'postbutton-stance-create';
    public final const COMPONENT_BUTTON_STANCE_UPDATE = 'postbutton-stance-update';
    public final const COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE = 'lazypostbutton-stance-createorupdate';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_BUTTON_STANCE_CREATE],
            [self::class, self::COMPONENT_BUTTON_STANCE_UPDATE],
            [self::class, self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE],
        );
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret[] = 'createStanceButtonLazy';
                break;
        }

        return $ret;
    }

    public function getButtoninnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $buttoninners = array(
            self::COMPONENT_BUTTON_STANCE_CREATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_STANCE_CREATE],
            self::COMPONENT_BUTTON_STANCE_UPDATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::COMPONENT_BUTTONINNER_STANCE_UPDATE],
            self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE => [UserStance_Module_Processor_ButtonInners::class, UserStance_Module_Processor_ButtonInners::COMPONENT_LAZYBUTTONINNER_STANCE_CREATEORUPDATE],
        );
        if ($buttoninner = $buttoninners[$component[1]] ?? null) {
            return $buttoninner;
        }

        return parent::getButtoninnerSubcomponent($component);
    }

    public function getTargetDynamicallyRenderedSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTON_STANCE_CREATE:
            case self::COMPONENT_BUTTON_STANCE_UPDATE:
            case self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE:
                return array(
                    [PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET],
                );
        }

        return parent::getTargetDynamicallyRenderedSubcomponents($component);
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_BUTTON_STANCE_CREATE:
            case self::COMPONENT_BUTTON_STANCE_UPDATE:
            case self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE:
                return POP_TARGET_ADDONS;
        }

        return parent::getLinktarget($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getBtnClass($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_BUTTON_STANCE_CREATE:
            case self::COMPONENT_BUTTON_STANCE_UPDATE:
            case self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret .= ' btn btn-link';
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE:
                $ret .= ' disabled';
                break;
        }

        return $ret;
    }

    public function getTitle(\PoP\ComponentModel\Component\Component $component, array &$props)
    {

        // Allow Events to have a different title
        $stance = sprintf(
            TranslationAPIFacade::getInstance()->__('%s, %s', 'pop-userstance-processors'),
            PoP_UserStanceProcessors_Utils::getWhatisyourvoteTitle('lc'),
            UserStance_Module_Processor_ButtonUtils::getFullviewAddstanceTitle()
        );
        $titles = array(
            self::COMPONENT_BUTTON_STANCE_CREATE => $stance,
            self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE => $stance,
            self::COMPONENT_BUTTON_STANCE_UPDATE => sprintf(
                TranslationAPIFacade::getInstance()->__('Edit your corresponding %s', 'pop-userstance-processors'),
                PoP_UserStance_PostNameUtils::getNameLc()
            ),
        );
        if ($title = $titles[$component[1]] ?? null) {
            return $title;
        }

        return parent::getTitle($component, $props);
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        $fields = array(
            self::COMPONENT_BUTTON_STANCE_CREATE => 'addStanceURL',
            self::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE => 'addStanceURL',
            self::COMPONENT_BUTTON_STANCE_UPDATE => 'editStanceURL',
        );
        if ($field = $fields[$component[1]] ?? null) {
            return $field;
        }

        return parent::getUrlField($component);
    }
}



