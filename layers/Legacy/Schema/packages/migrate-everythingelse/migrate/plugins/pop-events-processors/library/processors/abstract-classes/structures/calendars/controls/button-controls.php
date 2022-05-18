<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CalendarButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const MODULE_CALENDARBUTTONCONTROL_CALENDARPREV = 'calendarbuttoncontrol-calendarprev';
    public final const MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT = 'calendarbuttoncontrol-calendarnext';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV],
            [self::class, self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
                return TranslationAPIFacade::getInstance()->__('Previous month', 'em-popprocessors');

            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return TranslationAPIFacade::getInstance()->__('Next month', 'em-popprocessors');
        }

        return parent::getLabel($module, $props);
    }
    public function getIcon(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
                return 'glyphicon-chevron-left';

            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return 'glyphicon-chevron-right';
        }

        return parent::getIcon($module);
    }
    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                $classes = array(
                    self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV => 'calendar-prev',
                    self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT => 'calendar-next'
                );
                $class = $classes[$module[1]];

                $this->appendProp($module, $props, 'class', $class . ' fetchmore-btn-disable');
                // $calendar_target = $this->getProp($module, $props, 'calendar-target');
                // $this->mergeProp($module, $props, 'params', array(
                //     'data-target' => $calendar_target
                // ));
                break;
        }

        parent::initModelProps($module, $props);
    }
    public function getText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return null;
        }

        return parent::getText($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
                $this->addJsmethod($ret, 'controlCalendarPrev');
                break;

            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                $this->addJsmethod($ret, 'controlCalendarNext');
                break;
        }
        return $ret;
    }
}


