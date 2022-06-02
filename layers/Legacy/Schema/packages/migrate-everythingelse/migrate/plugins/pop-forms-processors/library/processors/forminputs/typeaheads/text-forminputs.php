<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TypeaheadTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const COMPONENT_FORMINPUT_TEXT_TYPEAHEAD = 'forminput-text-typeahead';
    public final const COMPONENT_FORMINPUT_TEXT_TYPEAHEADSEARCH = 'forminput-text-typeaheadsearch';
    public final const COMPONENT_FORMINPUT_TEXT_TYPEAHEADPROFILES = 'forminput-text-typeaheadprofiles';
    public final const COMPONENT_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT = 'forminput-text-typeaheadrelatedcontent';

    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FORMINPUT_TEXT_TYPEAHEAD,
            self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADSEARCH,
            self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADPROFILES,
            self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT,
        );
    }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADSEARCH:
                return sprintf(TranslationAPIFacade::getInstance()->__('Search %s', 'pop-coreprocessors'), $cmsapplicationapi->getSiteName());

            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADPROFILES:
                return TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors');

            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT:
                // return TranslationAPIFacade::getInstance()->__('Responded/Annotated by', 'pop-coreprocessors');
                // return TranslationAPIFacade::getInstance()->__('Post title', 'pop-coreprocessors');
                return TranslationAPIFacade::getInstance()->__('In response to', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($component, $props);
    }

    public function getJsmethods(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $ret = parent::getJsmethods($component, $props);

        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADSEARCH:
                $this->addJsmethod($ret, 'typeaheadSearchInput');
                break;
        }
        return $ret;
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FORMINPUT_TEXT_TYPEAHEADSEARCH:
                // Comment Leo 08/12/2017: Assign the input the "searchfor" name, so that it works to perform search
                // even when JS is disabled or fails
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getComponentProcessor([PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH])->getName([PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::COMPONENT_FILTERINPUT_SEARCH]);
        }
        
        return parent::getName($component);
    }
}



