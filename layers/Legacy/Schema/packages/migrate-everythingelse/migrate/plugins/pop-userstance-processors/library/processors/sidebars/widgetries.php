<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class UserStance_Module_Processor_Widgets extends PoP_Module_Processor_WidgetsBase
{
    public final const MODULE_WIDGET_STANCETARGET = 'widget-stancetarget';
    public final const MODULE_WIDGET_STANCES = 'widget-stances';
    public final const MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW = 'widget-stances-appendtoscript-fullview';
    public final const MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS = 'widget-stances-appendtoscript-details';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_WIDGET_STANCETARGET],
            [self::class, self::MODULE_WIDGET_STANCES],
            [self::class, self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW],
            [self::class, self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS],
        );
    }

    public function getLayoutSubmodules(array $component)
    {
        $ret = parent::getLayoutSubmodules($component);

        switch ($component[1]) {
            case self::MODULE_WIDGET_STANCETARGET:
                $ret[] = [PoP_Module_Processor_StanceTargetSubcomponentLayouts::class, PoP_Module_Processor_StanceTargetSubcomponentLayouts::MODULE_LAYOUT_STANCETARGET_ADDONS];
                break;

            case self::MODULE_WIDGET_STANCES:
                $ret[] = [UserStance_Module_Processor_StanceReferencedbyLayouts::class, UserStance_Module_Processor_StanceReferencedbyLayouts::MODULE_SUBCOMPONENT_STANCES];
                break;

            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS:
                $ret[] = [PoP_Module_Processor_StanceReferencesFramesLayouts::class, PoP_Module_Processor_StanceReferencesFramesLayouts::MODULE_LAYOUT_STANCES_APPENDTOSCRIPT];
                break;
        }

        return $ret;
    }

    public function getMenuTitle(array $component, array &$props)
    {
        $stances = PoP_UserStance_PostNameUtils::getNamesLc();
        $titles = array(
            self::MODULE_WIDGET_STANCETARGET => sprintf(
                TranslationAPIFacade::getInstance()->__('%s after reading...', 'pop-userstance-processors'),
                PoP_UserStance_PostNameUtils::getNameUc()
            ),
            self::MODULE_WIDGET_STANCES => $stances,
            self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW => $stances,
            self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS => $stances,
        );

        return $titles[$component[1]] ?? null;
    }
    public function getFontawesome(array $component, array &$props)
    {
        $fontawesomes = array(
            self::MODULE_WIDGET_STANCETARGET => 'fa-asterisk',
            self::MODULE_WIDGET_STANCES => 'fa-commenting-o',
            self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW => 'fa-commenting-o',
            self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS => 'fa-commenting-o',
        );

        return $fontawesomes[$component[1]] ?? null;
    }
    public function getBodyClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGET_STANCES:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS:
                return '';
        }

        return parent::getBodyClass($component, $props);
    }
    public function getItemWrapper(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGET_STANCES:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS:
                return '';
        }

        return parent::getItemWrapper($component, $props);
    }
    public function getWidgetClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGET_STANCES:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS:
                return '';
        }

        return parent::getWidgetClass($component, $props);
    }
    public function getTitleWrapperClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGET_STANCES:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS:
                return '';
        }

        return parent::getTitleWrapperClass($component, $props);
    }
    public function getTitleClass(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGET_STANCES:
                // case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW:
                // case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS:

                return '';
        }

        return parent::getTitleClass($component, $props);
    }
    public function getQuicklinkgroupSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGET_STANCES:
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_FULLVIEW:
                return [UserStance_Module_Processor_PostButtons::class, UserStance_Module_Processor_PostButtons::MODULE_BUTTON_STANCE_CREATE];
        }

        return parent::getQuicklinkgroupSubmodule($component);
    }
    public function collapsible(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_WIDGET_STANCES_APPENDTOSCRIPT_DETAILS:
                return true;
        }

        return parent::collapsible($component, $props);
    }
}


