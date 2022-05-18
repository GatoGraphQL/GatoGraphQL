<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_ContactUs_Module_Processor_GFForms extends PoP_Module_Processor_FormsBase
{
    public final const MODULE_FORM_CONTACTUS = 'form-contactus';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORM_CONTACTUS],
        );
    }

    public function getInnerSubmodule(array $module)
    {
        $inners = array(
            self::MODULE_FORM_CONTACTUS => [PoP_ContactUs_Module_Processor_GFFormInners::class, PoP_ContactUs_Module_Processor_GFFormInners::MODULE_FORMINNER_CONTACTUS],
        );

        if ($inner = $inners[$module[1]] ?? null) {
            return $inner;
        }

        return parent::getInnerSubmodule($module);
    }

    public function initModelProps(array $module, array &$props): void
    {
        switch ($module[1]) {
            case self::MODULE_FORM_CONTACTUS:
                $email = '';
                if (defined('POP_EMAILSENDER_INITIALIZED')) {
                    $email = PoP_EmailSender_Utils::getFromEmail();
                }
                if ($email = \PoP\Root\App::applyFilters(
                    'PoP_Module_Processor_GFForms:contactus:email',
                    $email
                )
                ) {
                     // Add the description. Allow Organik Fundraising to override the message
                    $description = sprintf(
                        '<p><em>%s</em></p>',
                        \PoP\Root\App::applyFilters(
                            'PoP_Module_Processor_GFForms:contactus:description',
                            sprintf(
                                TranslationAPIFacade::getInstance()->__('Please write an email to <a href="mailto:%1$s">%1$s</a>, or fill in the form below:', 'pop-genericforms'),
                                $email
                            ),
                            $email
                        )
                    );
                    $this->setProp($module, $props, 'description', $description);
                }
                break;
        }

        parent::initModelProps($module, $props);
    }
}



