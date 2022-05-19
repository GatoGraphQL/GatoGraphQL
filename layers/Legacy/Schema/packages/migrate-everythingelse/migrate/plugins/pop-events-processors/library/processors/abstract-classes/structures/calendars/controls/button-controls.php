<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CalendarButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV = 'calendarbuttoncontrol-calendarprev';
    public final const COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT = 'calendarbuttoncontrol-calendarnext';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV],
            [self::class, self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV:
                return TranslationAPIFacade::getInstance()->__('Previous month', 'em-popprocessors');

            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return TranslationAPIFacade::getInstance()->__('Next month', 'em-popprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getIcon(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV:
                return 'glyphicon-chevron-left';

            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return 'glyphicon-chevron-right';
        }

        return parent::getIcon($component);
    }
    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV:
            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT:
                $classes = array(
                    self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV => 'calendar-prev',
                    self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT => 'calendar-next'
                );
                $class = $classes[$component[1]];

                $this->appendProp($component, $props, 'class', $class . ' fetchmore-btn-disable');
                // $calendar_target = $this->getProp($component, $props, 'calendar-target');
                // $this->mergeProp($component, $props, 'params', array(
                //     'data-target' => $calendar_target
                // ));
                break;
        }

        parent::initModelProps($component, $props);
    }
    public function getText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV:
            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT:
                return null;
        }

        return parent::getText($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARPREV:
                $this->addJsmethod($ret, 'controlCalendarPrev');
                break;

            case self::COMPONENT_CALENDARBUTTONCONTROL_CALENDARNEXT:
                $this->addJsmethod($ret, 'controlCalendarNext');
                break;
        }
        return $ret;
    }
}


