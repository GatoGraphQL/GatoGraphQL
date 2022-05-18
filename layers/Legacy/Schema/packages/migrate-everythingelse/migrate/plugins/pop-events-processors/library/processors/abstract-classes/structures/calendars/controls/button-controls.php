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

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
                return TranslationAPIFacade::getInstance()->__('Previous month', 'em-popprocessors');

            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return TranslationAPIFacade::getInstance()->__('Next month', 'em-popprocessors');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getIcon(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
                return 'glyphicon-chevron-left';

            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return 'glyphicon-chevron-right';
        }

        return parent::getIcon($componentVariation);
    }
    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                $classes = array(
                    self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV => 'calendar-prev',
                    self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT => 'calendar-next'
                );
                $class = $classes[$componentVariation[1]];

                $this->appendProp($componentVariation, $props, 'class', $class . ' fetchmore-btn-disable');
                // $calendar_target = $this->getProp($componentVariation, $props, 'calendar-target');
                // $this->mergeProp($componentVariation, $props, 'params', array(
                //     'data-target' => $calendar_target
                // ));
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
    public function getText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARPREV:
            case self::MODULE_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return null;
        }

        return parent::getText($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
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


