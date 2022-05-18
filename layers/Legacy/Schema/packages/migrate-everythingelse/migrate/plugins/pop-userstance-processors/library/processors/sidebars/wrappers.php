<?php

class UserStance_Module_Processor_WidgetWrappers extends PoP_Module_Processor_ConditionWrapperBase
{
    public final const MODULE_WIDGETWRAPPER_STANCETARGET = 'widgetwrapper-stancetarget';
    public final const MODULE_WIDGETWRAPPER_STANCES = 'widgetwrapper-stances';
    public final const MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE = 'lazypostbuttonwrapper-stance-createorupdate';
    public final const MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE = 'postbuttonwrapper-stance-createorcreate';
    public final const MODULE_BUTTONGROUPWRAPPER_STANCECOUNT = 'postbuttongroupwrapper-stancecount';
    public final const MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW = 'widgetwrapper-opvotereferencedby-fullview';
    public final const MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS = 'widgetwrapper-opvotereferencedby-details';
    public final const MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT = 'buttonwrapper-opvote-createorupdate';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGETWRAPPER_STANCETARGET],
            [self::class, self::MODULE_WIDGETWRAPPER_STANCES],
            [self::class, self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW],
            [self::class, self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS],
            [self::class, self::MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE],
            [self::class, self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT],
            [self::class, self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE],
            [self::class, self::MODULE_BUTTONGROUPWRAPPER_STANCECOUNT],
        );
    }

    public function getConditionSucceededSubmodules(array $module)
    {
        $ret = parent::getConditionSucceededSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_STANCETARGET:
                $ret[] = [UserStance_Module_Processor_Widgets::class, UserStance_Module_Processor_Widgets::MODULE_WIDGET_STANCETARGET];
                break;

            case self::MODULE_WIDGETWRAPPER_STANCES:
                $ret[] = [UserStance_Module_Processor_Widgets::class, UserStance_Module_Processor_Widgets::MODULE_WIDGET_STANCES];
                break;

            case self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW:
                $ret[] = [UserStance_Module_Processor_Widgets::class, UserStance_Module_Processor_Widgets::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW];
                break;

            case self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS:
                $ret[] = [UserStance_Module_Processor_Widgets::class, UserStance_Module_Processor_Widgets::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS];
                break;

            case self::MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_PostButtons::class, UserStance_Module_Processor_PostButtons::MODULE_LAZYBUTTON_STANCE_CREATEORUPDATE];
                break;

            case self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
                $ret[] = [PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayouts::class, PoP_Module_Processor_CreateOrUpdateStanceButtonScriptFrameLayouts::MODULE_BUTTON_STANCE_CREATEORUPDATE_APPENDTOSCRIPT];
                break;

            case self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_PostButtons::class, UserStance_Module_Processor_PostButtons::MODULE_BUTTON_STANCE_UPDATE];
                break;

            case self::MODULE_BUTTONGROUPWRAPPER_STANCECOUNT:
                $ret[] = [UserStance_Module_Processor_QuicklinkButtonGroups::class, UserStance_Module_Processor_QuicklinkButtonGroups::MODULE_QUICKLINKBUTTONGROUP_POSTSTANCE];
                break;
        }

        return $ret;
    }

    public function getConditionFailedSubmodules(array $module)
    {
        $ret = parent::getConditionFailedSubmodules($module);

        switch ($module[1]) {
            case self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $ret[] = [UserStance_Module_Processor_PostButtons::class, UserStance_Module_Processor_PostButtons::MODULE_BUTTON_STANCE_CREATE];
                break;

            case self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_StanceReferencesFramesLayouts::class, PoP_Module_Processor_StanceReferencesFramesLayouts::MODULE_LAYOUT_STANCESEMPTY_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_STANCETARGET:
                $this->appendProp($module, $props, 'class', 'references');
                break;

            case self::MODULE_WIDGETWRAPPER_STANCES:
            case self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS:
                $this->appendProp($module, $props, 'class', 'referencedby clearfix');
                break;

            case self::MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $classes = array(
                    self::MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE => 'createorupdatestance',
                );
                $this->setProp($module, $props, 'appendable', true);
                $this->setProp($module, $props, 'appendable-class', $classes[$module[1]] ?? null);
                break;
        }

        switch ($module[1]) {
            case self::MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE:
            case self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
            case self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE:
                $this->appendProp($module, $props, 'class', 'inline');
                break;

            case self::MODULE_BUTTONGROUPWRAPPER_STANCECOUNT:
                $this->appendProp($module, $props, 'class', 'pop-stancecount-wrapper');
                break;
        }

        parent::initModelProps($module, $props);
    }

    public function getConditionField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_WIDGETWRAPPER_STANCETARGET:
                return 'hasStanceTarget';

            case self::MODULE_WIDGETWRAPPER_STANCES:
            case self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGETWRAPPER_STANCES_APPENDTOSCRIPT_DETAILS:
            case self::MODULE_BUTTONGROUPWRAPPER_STANCECOUNT:
                return 'hasStances';

            case self::MODULE_LAZYBUTTONWRAPPER_STANCE_CREATEORUPDATE:
            case self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE_APPENDTOSCRIPT:
            case self::MODULE_BUTTONWRAPPER_STANCE_CREATEORUPDATE:
                return 'hasLoggedInUserStances';
        }

        return null;
    }
}



