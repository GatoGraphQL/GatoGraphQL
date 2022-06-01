<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const COMPONENT_FORM_FLAG = 'form-flag';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORM_FLAG,
        );
    }

    public function getInnerSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        $inners = array(
            self::COMPONENT_FORM_FLAG => [PoP_ContentCreation_Module_Processor_GFFormInners::class, PoP_ContentCreation_Module_Processor_GFFormInners::COMPONENT_FORMINNER_FLAG],
        );

        if ($inner = $inners[$component->name] ?? null) {
            return $inner;
        }

        return parent::getInnerSubcomponent($component);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        switch ($component->name) {
            case self::COMPONENT_FORM_FLAG:
                // Add the description
                $description = sprintf(
                    '<p><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('Based on our users\' feedback, we will consider removing this post. You will receive a confirmation by email.', 'pop-genericforms')
                );
                $this->setProp($component, $props, 'description', $description);
                break;
        }

        parent::initModelProps($component, $props);
    }
}



