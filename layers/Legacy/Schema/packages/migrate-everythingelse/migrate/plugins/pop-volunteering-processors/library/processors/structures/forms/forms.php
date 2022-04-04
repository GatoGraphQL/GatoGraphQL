<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Volunteering_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_VOLUNTEER = 'form-volunteer';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_VOLUNTEER],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FORM_VOLUNTEER => [PoP_Volunteering_Module_Processor_GFFormInners::class, PoP_Volunteering_Module_Processor_GFFormInners::MODULE_FORMINNER_VOLUNTEER],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORM_VOLUNTEER:
                // Add the description
                $description = sprintf(
                    '<p><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('We will send the info below to the organizers, who should then get in touch with you.', 'pop-genericforms')
                );
                $this->setProp($module, $props, 'description', $description);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



