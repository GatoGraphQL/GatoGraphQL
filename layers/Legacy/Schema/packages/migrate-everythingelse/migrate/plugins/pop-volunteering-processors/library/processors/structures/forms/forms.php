<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_VOLUNTEER = 'form-volunteer';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FORM_VOLUNTEER],
        );
    }

    public function getInnerSubmodule(array $component)
    {
        $inners = array(
            self::COMPONENT_FORM_VOLUNTEER => [PoP_Volunteering_Module_Processor_GFFormInners::class, PoP_Volunteering_Module_Processor_GFFormInners::COMPONENT_FORMINNER_VOLUNTEER],
        );

        if ($inner = $inners[$component[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($component);
    }

    public function initModelProps(array $component, array &$props): void
    {
        switch ($component[1]) {
            case self::COMPONENT_FORM_VOLUNTEER:
                // Add the description
                $description = sprintf(
                    '<p><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('We will send the info below to the organizers, who should then get in touch with you.', 'pop-genericforms')
                );
                $this->setProp($component, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



