<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SearchFilterInputProcessor;
use PoPCMSSchema\Users\FilterInputProcessors\NameFilterInputProcessor;

class PoP_Module_Processor_TextFilterInputs extends PoP_Module_Processor_TextFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERINPUT_SEARCH = 'filterinput-search';
    public final const COMPONENT_FILTERINPUT_HASHTAGS = 'filterinput-hashtags';
    public final const COMPONENT_FILTERINPUT_NAME = 'filterinput-name';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?SearchFilterInputProcessor $searchFilterInputProcessor = null;
    private ?NameFilterInputProcessor $nameFilterInputProcessor = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setSearchFilterInputProcessor(SearchFilterInputProcessor $searchFilterInputProcessor): void
    {
        $this->searchFilterInputProcessor = $searchFilterInputProcessor;
    }
    final protected function getSearchFilterInputProcessor(): SearchFilterInputProcessor
    {
        return $this->searchFilterInputProcessor ??= $this->instanceManager->getInstance(SearchFilterInputProcessor::class);
    }
    final public function setNameFilterInputProcessor(NameFilterInputProcessor $nameFilterInputProcessor): void
    {
        $this->nameFilterInputProcessor = $nameFilterInputProcessor;
    }
    final protected function getNameFilterInputProcessor(): NameFilterInputProcessor
    {
        return $this->nameFilterInputProcessor ??= $this->instanceManager->getInstance(NameFilterInputProcessor::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_SEARCH],
            [self::class, self::COMPONENT_FILTERINPUT_HASHTAGS],
            [self::class, self::COMPONENT_FILTERINPUT_NAME],
        );
    }

    /**
     * @todo Migrate from [FilterInputProcessor::class, FilterInputProcessor::NAME] to FilterInputProcessorInterface
     */
    public function getFilterInput(array $component): ?FilterInputProcessorInterface
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_SEARCH => $this->getSearchFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_NAME => $this->getNameFilterInputProcessor(),
            self::COMPONENT_FILTERINPUT_HASHTAGS => [PoP_Module_Processor_FormsFilterInputProcessor::class, PoP_Module_Processor_FormsFilterInputProcessor::FILTERINPUT_HASHTAGS],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_FILTERINPUT_SEARCH:
    //         case self::COMPONENT_FILTERINPUT_HASHTAGS:
    //         case self::COMPONENT_FILTERINPUT_NAME:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_SEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'pop-coreprocessors');

            case self::COMPONENT_FILTERINPUT_HASHTAGS:
                return TranslationAPIFacade::getInstance()->__('Hashtags', 'pop-coreprocessors');

            case self::COMPONENT_FILTERINPUT_NAME:
                return TranslationAPIFacade::getInstance()->__('Name', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_SEARCH:
            case self::COMPONENT_FILTERINPUT_HASHTAGS:
            case self::COMPONENT_FILTERINPUT_NAME:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::COMPONENT_FILTERINPUT_SEARCH => 'searchfor',
                    self::COMPONENT_FILTERINPUT_HASHTAGS => 'tags',
                    self::COMPONENT_FILTERINPUT_NAME => 'nombre',
                );
                return $names[$component[1]];
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::COMPONENT_FILTERINPUT_SEARCH => $this->stringScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_HASHTAGS => $this->stringScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_NAME => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_SEARCH => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_HASHTAGS => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_NAME => $translationAPI->__('', ''),
            default => null,
        };
    }
}



