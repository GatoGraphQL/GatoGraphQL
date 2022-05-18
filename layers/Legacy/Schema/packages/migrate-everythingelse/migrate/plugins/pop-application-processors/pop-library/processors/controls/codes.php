<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CustomCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL = 'code-updownvoteundoupdownvotepost-label';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL],
        );
    }

    public function getCode(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL:
                // Allow TPP Debate website to override this label with "Agree?"
                $labels = array(
                    self::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL => \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomCodes:updownvote:label', TranslationAPIFacade::getInstance()->__('Important?', 'poptheme-wassup')),
                );

                return sprintf(
                    '<span class="btn btn-link btn-compact btn-span pop-functionbutton">%s</span>',
                    $labels[$componentVariation[1]]
                );
        }

        return parent::getCode($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($componentVariation, $props, 'resourceloader', 'functionbutton');
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}


