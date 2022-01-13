<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContentCreation_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public const MODULE_FORM_FLAG = 'form-flag';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_FLAG],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FORM_FLAG => [PoP_ContentCreation_Module_Processor_GFFormInners::class, PoP_ContentCreation_Module_Processor_GFFormInners::MODULE_FORMINNER_FLAG],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORM_FLAG:
                // Add the description
                $description = sprintf(
                    '<p><em>%s</em></p>',
                    TranslationAPIFacade::getInstance()->__('Based on our users\' feedback, we will consider removing this post. You will receive a confirmation by email.', 'pop-genericforms')
                );
                $this->setProp($module, $props, 'description', $description);
                break;
        }

        parent::initModelProps($module, $props);
    }
}



