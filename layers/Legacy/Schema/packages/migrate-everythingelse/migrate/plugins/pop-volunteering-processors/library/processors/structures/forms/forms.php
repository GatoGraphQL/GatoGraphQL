<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_VOLUNTEER = 'form-volunteer';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_VOLUNTEER],
        );
    }

    public function getInnerSubmodule(array $componentVariation)
    {
        $inners = array(
            self::MODULE_FORM_VOLUNTEER => [PoP_Volunteering_Module_Processor_GFFormInners::class, PoP_Volunteering_Module_Processor_GFFormInners::MODULE_FORMINNER_VOLUNTEER],
        );

        if ($inner = $inners[$componentVariation[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($componentVariation);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FORM_VOLUNTEER:
                // Add the description
                $description = sprintf(
                    '<p><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('We will send the info below to the organizers, who should then get in touch with you.', 'pop-genericforms')
                );
                $this->setProp($componentVariation, $props, 'description', $description);
                break;
        }

        parent::initModelProps($componentVariation, $props);
    }
}



