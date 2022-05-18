<?php

class UserStance_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const COMPONENT_WIDGETWRAPPER_STANCETARGET = 'widgetwrapper-stancetarget';
    public final const COMPONENT_WIDGETWRAPPER_STANCES = 'widgetwrapper-stances';
    public final const COMPONENT_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE = 'lazypostbuttonwrapper-stance-createorupdate';
    public final const COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE = 'postbuttonwrapper-stance-createorcreate';
    public final const COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT = 'postbuttongroupwrapper-stancecount';
    public final const COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW = 'widgetwrapper-opvotereferencedby-fullview';
    public final const COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS = 'widgetwrapper-opvotereferencedby-details';
    public final const COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT = 'buttonwrapper-opvote-createorupdate';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_WIDGETWRAPPER_STANCETARGET],
            [self::class, self::COMPONENT_WIDGETWRAPPER_STANCES],
            [self::class, self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW],
            [self::class, self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS],
            [self::class, self::COMPONENT_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE],
            [self::class, self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT],
            [self::class, self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE],
            [self::class, self::COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT],
        );
    }

    public function getConditionSucceededSubmodules(array $component)
    {
        $ret = parent::getConditionSucceededSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_STANCETARGET:
                $ret[] = [UserStance_Module_Processor_Widgets::class, UserStance_Module_Processor_Widgets::COMPONENT_WIDGET_STANCETARGET];
                break;

            case self::COMPONENT_WIDGETWRAPPER_STANCES:
                $ret[] = [UserStance_Module_Processor_Widgets::class, UserStance_Module_Processor_Widgets::COMPONENT_WIDGET_STANCES];
                break;

            case self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [UserStance_Module_Processor_Widgets::class, UserStance_Module_Processor_Widgets::COMPONENT_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW];
                break;

            case self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS:
                $ret[] = [UserStance_Module_Processor_Widgets::class, UserStance_Module_Processor_Widgets::COMPONENT_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS];
                break;

            case self::COMPONENT_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_PostButtons::class, UserStance_Module_Processor_PostButtons::COMPONENT_LAZYBUTTON_STANCE_CREATEORUPDATE];
                break;

            case self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayouts::class, PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayouts::COMPONENT_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT];
                break;

            case self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_PostButtons::class, UserStance_Module_Processor_PostButtons::COMPONENT_BUTTON_STANCE_UPDATE];
                break;

            case self::COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT:
                $ret[] = [UserStance_Module_Processor_QuicklinkButtonGroups::class, UserStance_Module_Processor_QuicklinkButtonGroups::COMPONENT_QUICKLINKBUTTONGROUP_POSTSTANCE];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubmodules(array $component)
    {
        $ret = parent::getConditionFailedSubmodules($component);

        switch ($component[1]) {
            case self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_PostButtons::class, UserStance_Module_Processor_PostButtons::COMPONENT_BUTTON_STANCE_CREATE];
                break;

            case self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_StanceReferencesFramesLayouts::class, PoP_Module_Processor_StanceReferencesFramesLayouts::COMPONENT_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_STANCETARGET:
                $this->appendProp($component, $props, 'class', 'references');
                break;

            case self::COMPONENT_WIDGETWRAPPER_STANCES:
            case self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS:
                $this->appendProp($component, $props, 'class', 'referencedby clearfix');
                break;

            case self::COMPONENT_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $classes = array(
                    self::COMPONENT_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE => 'createorupdatestance',
                );
                $this->setProp($component, $props, 'appendable', true);
                $this->setProp($component, $props, 'appendable-class', $classes[$component[1]] ?? null);
                break;
        }

        switch ($component[1]) {
            case self::COMPONENT_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE:
            case self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
            case self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $this->appendProp($component, $props, 'class', 'inline');
                break;

            case self::COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT:
                $this->appendProp($component, $props, 'class', 'pop-stancecount-wrapper');
                break;
        }

        parent::initModelProps($component, $props);
    }

    public function getConditionField(array $component): ?string
    {
        switch ($component[1]) {
            case self::COMPONENT_WIDGETWRAPPER_STANCETARGET:
                return 'hasStanceTarget';

            case self::COMPONENT_WIDGETWRAPPER_STANCES:
            case self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::COMPONENT_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS:
            case self::COMPONENT_BUTTONGROUPWRAPPER_STANCECOUNT:
                return 'hasStances';

            case self::COMPONENT_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE:
            case self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
            case self::COMPONENT_BUTTONWRAPPER_STANCE_CREATEORUPDATE:
                return 'hasLoggedInUserStances';
        }

        return null;
    }
}



