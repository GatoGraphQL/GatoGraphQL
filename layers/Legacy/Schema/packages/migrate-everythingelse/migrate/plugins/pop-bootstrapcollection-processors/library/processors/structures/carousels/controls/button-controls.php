<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CarouselButtonControls extends PoP_Module_Processor_ButtonControlsBase
{
    public final const MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV = 'carouselbuttoncontrol-carouselprev';
    public final const MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT = 'carouselbuttoncontrol-carouselnext';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV],
            [self::class, self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT],
        );
    }

    public function getLabel(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                return TranslationAPIFacade::getInstance()->__('Previous', 'pop-coreprocessors');

            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return TranslationAPIFacade::getInstance()->__('Next', 'pop-coreprocessors');
        }

        return parent::getLabel($componentVariation, $props);
    }
    public function getIcon(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                return 'glyphicon-chevron-left';

            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return 'glyphicon-chevron-right';
        }

        return parent::getIcon($componentVariation);
    }
    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                $classes = array(
                    self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV => 'carousel-prev',
                    self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT => 'carousel-next'
                );
                $class = $classes[$componentVariation[1]];

                $this->appendProp($componentVariation, $props, 'class', $class . ' fetchmore-btn-disable');
                $carousel_target = $this->getProp($componentVariation, $props, 'carousel-target');
                $this->mergeProp(
                    $componentVariation,
                    $props,
                    'params',
                    array(
                        'data-target' => $carousel_target
                    )
                );
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
    public function getText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return null;
        }

        return parent::getText($componentVariation, $props);
    }

    public function getJsmethods(array $componentVariation, array &$props)
    {
        $ret = parent::getJsmethods($componentVariation, $props);

        switch ($componentVariation[1]) {
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


