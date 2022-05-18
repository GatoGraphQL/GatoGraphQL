<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CustomCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public final const COMPONENT_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL = 'code-updownvoteundoupdownvotepost-label';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL],
        );
    }

    public function getCode(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL:
                // Allow TPP Debate website to override this label with "Agree?"
                $labels = array(
                    self::COMPONENT_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL => \PoP\Root\App::applyFilters('PoP_Module_Processor_CustomCodes:updownvote:label', TranslationAPIFacade::getInstance()->__('Important?', 'poptheme-wassup')),
                );

                return sprintf(
                    '<span class="btn btn-link btn-compact btn-span pop-functionbutton">%s</span>',
                    $labels[$component[1]]
                );
        }

        return parent::getCode($component, $props);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL:
                // Artificial property added to identify the template when adding component-resources
                $this->setProp($component, $props, 'resourceloader', 'functionbutton');
                break;
        }

        parent::initModelProps($component, $props);
    }
}


