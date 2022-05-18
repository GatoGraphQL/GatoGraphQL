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

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                return TranslationAPIFacade::getInstance()->__('Previous', 'pop-coreprocessors');

            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return TranslationAPIFacade::getInstance()->__('Next', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }
    public function getIcon(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
                return 'glyphicon-chevron-left';

            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return 'glyphicon-chevron-right';
        }

        return parent::getIcon($module);
    }
    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                $classes = array(
                    self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV => 'carousel-prev',
                    self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT => 'carousel-next'
                );
                $class = $classes[$module[1]];

                $this->appendProp($module, $props, 'class', $class . ' fetchmore-btn-disable');
                $carousel_target = $this->getProp($module, $props, 'carousel-target');
                $this->mergeProp(
                    $module,
                    $props,
                    'params',
                    array(
                        'data-target' => $carousel_target
                    )
                );
                break;
        }

        parent::initModelProps($module, $props);
    }
    public function getText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELPREV:
            case self::MODULE_CAROUSELBUTTONCONTROL_CAROUSELNEXT:
                return null;
        }

        return parent::getText($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
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


