<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class PoP_Module_Processor_TypeaheadTextFormInputs extends PoP_Module_Processor_TextFormInputsBase
{
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEAD = 'forminput-text-typeahead';
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEADSEARCH = 'forminput-text-typeaheadsearch';
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEADPROFILES = 'forminput-text-typeaheadprofiles';
    public final const MODULE_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT = 'forminput-text-typeaheadrelatedcontent';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEAD],
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADSEARCH],
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADPROFILES],
            [self::class, self::MODULE_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT],
        );
    }

    public function getLabelText(array $module, array &$props)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADSEARCH:
                return sprintf(TranslationAPIFacade::getInstance()->__('Search %s', 'pop-coreprocessors'), $cmsapplicationapi->getSiteName());

            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADPROFILES:
                return TranslationAPIFacade::getInstance()->__('Author(s)', 'pop-coreprocessors');

            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT:
                // return TranslationAPIFacade::getInstance()->__('Responded/Annotated by', 'pop-coreprocessors');
                // return TranslationAPIFacade::getInstance()->__('Post title', 'pop-coreprocessors');
                return TranslationAPIFacade::getInstance()->__('In response to', 'pop-coreprocessors');
        }
        
        return parent::getLabelText($module, $props);
    }

    public function getJsmethods(array $module, array &$props)
    {
        $ret = parent::getJsmethods($module, $props);

        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADSEARCH:
                $this->addJsmethod($ret, 'typeaheadSearchInput');
                break;
        }
        return $ret;
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FORMINPUT_TEXT_TYPEAHEADSEARCH:
                // Comment Leo 08/12/2017: Assign the input the "searchfor" name, so that it works to perform search
                // even when JS is disabled or fails
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                return $componentprocessor_manager->getProcessor([PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH])->getName([PoP_Module_Processor_TextFilterInputs::class, PoP_Module_Processor_TextFilterInputs::MODULE_FILTERINPUT_SEARCH]);
        }
        
        return parent::getName($module);
    }
}



