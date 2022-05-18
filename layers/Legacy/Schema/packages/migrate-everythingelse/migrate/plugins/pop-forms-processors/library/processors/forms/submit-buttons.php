<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const MODULE_SUBMITBUTTON_SUBMIT = 'submitbutton-submit';
    public final const MODULE_SUBMITBUTTON_OK = 'submitbutton-ok';
    public final const MODULE_SUBMITBUTTON_SEND = 'submitbutton-send';
    public final const MODULE_SUBMITBUTTON_SAVE = 'submitbutton-save';
    public final const MODULE_SUBMITBUTTON_UPDATE = 'submitbutton-update';
    public final const MODULE_SUBMITBUTTON_SEARCH = 'submitbutton-search';

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_SUBMITBUTTON_SUBMIT],
            [self::class, self::MODULE_SUBMITBUTTON_OK],
            [self::class, self::MODULE_SUBMITBUTTON_SEND],
            [self::class, self::MODULE_SUBMITBUTTON_SAVE],
            [self::class, self::MODULE_SUBMITBUTTON_UPDATE],
            [self::class, self::MODULE_SUBMITBUTTON_SEARCH],
        );
    }

    public function getLabel(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_SUBMIT:
                return TranslationAPIFacade::getInstance()->__('Submit', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_OK:
                return TranslationAPIFacade::getInstance()->__('OK', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_SEND:
                return TranslationAPIFacade::getInstance()->__('Send', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_SAVE:
                return TranslationAPIFacade::getInstance()->__('Save', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_UPDATE:
                return TranslationAPIFacade::getInstance()->__('Update', 'pop-coreprocessors');

            case self::MODULE_SUBMITBUTTON_SEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'pop-coreprocessors');
        }

        return parent::getLabel($module, $props);
    }

    public function getBtnClass(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_SEARCH:
                return 'btn btn-info';
        }

        return parent::getBtnClass($module, $props);
    }

    public function getFontawesome(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_SEARCH:
                return 'fa fa-search';
        }

        return parent::getFontawesome($module, $props);
    }

    public function getLoadingText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_SUBMITBUTTON_SUBMIT:
            case self::MODULE_SUBMITBUTTON_SEND:
            case self::MODULE_SUBMITBUTTON_SAVE:
            case self::MODULE_SUBMITBUTTON_UPDATE:
                $loadings = array(
                    self::MODULE_SUBMITBUTTON_SUBMIT => TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-forms-processors'),
                    self::MODULE_SUBMITBUTTON_SEND => TranslationAPIFacade::getInstance()->__('Sending...', 'pop-forms-processors'),
                    self::MODULE_SUBMITBUTTON_SAVE => TranslationAPIFacade::getInstance()->__('Saving...', 'pop-forms-processors'),
                    self::MODULE_SUBMITBUTTON_UPDATE => TranslationAPIFacade::getInstance()->__('Updating...', 'pop-forms-processors'),
                );

                return $loadings[$module[1]];
        }
        
        return parent::getLoadingText($module, $props);
    }
}


