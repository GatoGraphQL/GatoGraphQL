<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CarouselButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV = 'carouselbuttoncontrol-carouselprev';
    public final const MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT = 'carouselbuttoncontrol-carouselnext';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV],
            [self::class, self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT],
        );
    }

    public function getLabel(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                return TranslationAPIFacade::getInstance()->__('Previous', 'pop-coreprocessors');

            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return TranslationAPIFacade::getInstance()->__('Next', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }
    public function getIcon(array $component)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                return 'glyphicon-chevron-left';

            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return 'glyphicon-chevron-right';
        }

        return parent::getIcon($component);
    }
    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                $classes = array(
                    self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV => 'carousel-prev',
                    self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT => 'carousel-next'
                );
                $class = $classes[$component[1]];

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
    public function getText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return null;
        }

        return parent::getText($component, $props);
    }

    public function getJsmethods(array $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                $this->addJsmethod($ret, 'controlCarouselPrev');
                $this->addJsmethod($ret, 'fetchMoreDisable');
                break;

            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                $this->addJsmethod($ret, 'controlCarouselNext');
                $this->addJsmethod($ret, 'fetchMoreDisable');
                break;
        }
        return $ret;
    }
}


