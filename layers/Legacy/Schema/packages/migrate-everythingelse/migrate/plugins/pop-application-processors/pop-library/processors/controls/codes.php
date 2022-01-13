<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_CustomCodes extends PoP_Module_Processor_HTMLCodesBase
{
    public const MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL = 'code-updownvoteundoupdownvotepost-label';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL],
        );
    }

    public function getCode(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL:
                // Allow TPP Debate website to override this label with "Agree?"
                $labels = array(
                    self::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL => \PoP\Root\App::getHookManager()->applyFilters('PoP_Module_Processor_CustomCodes:updownvote:label', TranslationAPIFacade::getInstance()->__('Important?', 'poptheme-wassup')),
                );

                return sprintf(
                    '<span class="btn btn-link btn-compact btn-span pop-functionbutton">%s</span>',
                    $labels[$module[1]]
                );
        }

        return parent::getCode($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_CODE_UPDOWNVOTEUNDOUPDOWNVOTEPOST_LABEL:
                // Artificial property added to identify the template when adding module-resources
                $this->setProp($module, $props, 'resourceloader', 'functionbutton');
                break;
        }

        parent::initModelProps($module, $props);
    }
}


