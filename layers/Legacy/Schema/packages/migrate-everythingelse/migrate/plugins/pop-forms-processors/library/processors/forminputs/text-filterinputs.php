<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\Users\FilterInputs\NameFilterInput;

class PoP_Module_Processor_TextFilterInputs extends PoP_Module_Processor_TextFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERINPUT_SEARCH = 'filterinput-search';
    public final const COMPONENT_FILTERINPUT_HASHTAGS = 'filterinput-hashtags';
    public final const COMPONENT_FILTERINPUT_NAME = 'filterinput-name';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?SearchFilterInput $searchFilterInput = null;
    private ?NameFilterInput $nameFilterInput = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setSearchFilterInput(SearchFilterInput $searchFilterInput): void
    {
        $this->searchFilterInput = $searchFilterInput;
    }
    final protected function getSearchFilterInput(): SearchFilterInput
    {
        return $this->searchFilterInput ??= $this->instanceManager->getInstance(SearchFilterInput::class);
    }
    final public function setNameFilterInput(NameFilterInput $nameFilterInput): void
    {
        $this->nameFilterInput = $nameFilterInput;
    }
    final protected function getNameFilterInput(): NameFilterInput
    {
        return $this->nameFilterInput ??= $this->instanceManager->getInstance(NameFilterInput::class);
    }

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_SEARCH,
            self::COMPONENT_FILTERINPUT_HASHTAGS,
            self::COMPONENT_FILTERINPUT_NAME,
        );
    }

    /**
     * @todo Migrate from [FilterInput::class, FilterInput::NAME] to FilterInputInterface
     */
    public function getFilterInput(\PoP\ComponentModel\Component\Component $component): ?FilterInputInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_SEARCH => $this->getSearchFilterInput(),
            self::COMPONENT_FILTERINPUT_NAME => $this->getNameFilterInput(),
            self::COMPONENT_FILTERINPUT_HASHTAGS => [PoP_Module_Processor_FormsFilterInput::class, PoP_Module_Processor_FormsFilterInput::FILTERINPUT_HASHTAGS],
            default => null,
        };
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_FILTERINPUT_SEARCH:
    //         case self::COMPONENT_FILTERINPUT_HASHTAGS:
    //         case self::COMPONENT_FILTERINPUT_NAME:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_SEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'pop-coreprocessors');

            case self::COMPONENT_FILTERINPUT_HASHTAGS:
                return TranslationAPIFacade::getInstance()->__('Hashtags', 'pop-coreprocessors');

            case self::COMPONENT_FILTERINPUT_NAME:
                return TranslationAPIFacade::getInstance()->__('Name', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_SEARCH:
            case self::COMPONENT_FILTERINPUT_HASHTAGS:
            case self::COMPONENT_FILTERINPUT_NAME:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::COMPONENT_FILTERINPUT_SEARCH => 'searchfor',
                    self::COMPONENT_FILTERINPUT_HASHTAGS => 'tags',
                    self::COMPONENT_FILTERINPUT_NAME => 'nombre',
                );
                return $names[$component->name];
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(\PoP\ComponentModel\Component\Component $component): InputTypeResolverInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_SEARCH => $this->stringScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_HASHTAGS => $this->stringScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_NAME => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_SEARCH => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_HASHTAGS => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_NAME => $translationAPI->__('', ''),
            default => null,
        };
    }
}



