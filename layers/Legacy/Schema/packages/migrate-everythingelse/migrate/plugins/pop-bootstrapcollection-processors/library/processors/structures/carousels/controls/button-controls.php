<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CarouselButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV = 'carouselbuttoncontrol-carouselprev';
    public final const COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT = 'carouselbuttoncontrol-carouselnext';

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV,
            self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT,
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                return TranslationAPIFacade::getInstance()->__('Previous', 'pop-coreprocessors');

            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return TranslationAPIFacade::getInstance()->__('Next', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getIcon(\PoP\ComponentModel\Component\Component $component)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                return 'glyphicon-chevron-left';

            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return 'glyphicon-chevron-right';
        }

        return parent::getIcon($component);
    }
    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV:
            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                $classes = array(
                    self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV => 'carousel-prev',
                    self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT => 'carousel-next'
                );
                $class = $classes[$component->name];

                $this->appendProp($component, $props, 'class', $class . ' fetchmore-btn-disable');
                $carousel_target = $this->getProp($component, $props, 'carousel-target');
                $this->mergeProp(
                    $component,
                    $props,
                    'params',
                    array(
                        'data-target' => $carousel_target
                    )
                );
                break;
        }

        parent::initModelProps($component, $props);
    }
    public function getText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV:
            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return null;
        }

        return parent::getText($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                $this->addJsmethod($ret, 'controlCarouselPrev');
                $this->addJsmethod($ret, 'fetchMoreDisable');
                break;

            case self::COMPONENT_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                $this->addJsmethod($ret, 'controlCarouselNext');
                $this->addJsmethod($ret, 'fetchMoreDisable');
                break;
        }
        return $ret;
    }
}


