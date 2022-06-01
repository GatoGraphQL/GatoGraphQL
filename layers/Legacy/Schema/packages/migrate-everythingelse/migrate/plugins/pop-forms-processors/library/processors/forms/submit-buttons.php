<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_SubmitButtons extends PoP_Module_Processor_SubmitButtonsBase
{
    public final const COMPONENT_SUBMITBUTTON_SUBMIT = 'submitbutton-submit';
    public final const COMPONENT_SUBMITBUTTON_OK = 'submitbutton-ok';
    public final const COMPONENT_SUBMITBUTTON_SEND = 'submitbutton-send';
    public final const COMPONENT_SUBMITBUTTON_SAVE = 'submitbutton-save';
    public final const COMPONENT_SUBMITBUTTON_UPDATE = 'submitbutton-update';
    public final const COMPONENT_SUBMITBUTTON_SEARCH = 'submitbutton-search';

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_SUBMITBUTTON_SUBMIT],
            [self::class, self::COMPONENT_SUBMITBUTTON_OK],
            [self::class, self::COMPONENT_SUBMITBUTTON_SEND],
            [self::class, self::COMPONENT_SUBMITBUTTON_SAVE],
            [self::class, self::COMPONENT_SUBMITBUTTON_UPDATE],
            [self::class, self::COMPONENT_SUBMITBUTTON_SEARCH],
        );
    }

    public function getLabel(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMITBUTTON_SUBMIT:
                return TranslationAPIFacade::getInstance()->__('Submit', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_OK:
                return TranslationAPIFacade::getInstance()->__('OK', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_SEND:
                return TranslationAPIFacade::getInstance()->__('Send', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_SAVE:
                return TranslationAPIFacade::getInstance()->__('Save', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_UPDATE:
                return TranslationAPIFacade::getInstance()->__('Update', 'pop-coreprocessors');

            case self::COMPONENT_SUBMITBUTTON_SEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'pop-coreprocessors');
        }

        return parent::getLabel($component, $props);
    }

    public function getBtnClass(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMITBUTTON_SEARCH:
                return 'btn btn-info';
        }

        return parent::getBtnClass($component, $props);
    }

    public function getFontawesome(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMITBUTTON_SEARCH:
                return 'fa fa-search';
        }

        return parent::getFontawesome($component, $props);
    }

    public function getLoadingText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_SUBMITBUTTON_SUBMIT:
            case self::COMPONENT_SUBMITBUTTON_SEND:
            case self::COMPONENT_SUBMITBUTTON_SAVE:
            case self::COMPONENT_SUBMITBUTTON_UPDATE:
                $loadings = array(
                    self::COMPONENT_SUBMITBUTTON_SUBMIT => TranslationAPIFacade::getInstance()->__('Submitting...', 'pop-forms-processors'),
                    self::COMPONENT_SUBMITBUTTON_SEND => TranslationAPIFacade::getInstance()->__('Sending...', 'pop-forms-processors'),
                    self::COMPONENT_SUBMITBUTTON_SAVE => TranslationAPIFacade::getInstance()->__('Saving...', 'pop-forms-processors'),
                    self::COMPONENT_SUBMITBUTTON_UPDATE => TranslationAPIFacade::getInstance()->__('Updating...', 'pop-forms-processors'),
                );

                return $loadings[$component[1]];
        }
        
        return parent::getLoadingText($component, $props);
    }
}


