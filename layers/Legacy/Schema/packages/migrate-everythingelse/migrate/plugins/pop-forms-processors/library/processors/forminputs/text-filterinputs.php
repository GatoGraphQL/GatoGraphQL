<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;
use PoPCMSSchema\Users\FilterInputProcessors\FilterInputProcessor as UserFilterInputProcessor;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_TextFilterInputs extends PoP_Module_Processor_TextFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERINPUT_SEARCH = 'filterinput-search';
    public final const MODULE_FILTERINPUT_HASHTAGS = 'filterinput-hashtags';
    public final const MODULE_FILTERINPUT_NAME = 'filterinput-name';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_SEARCH],
            [self::class, self::MODULE_FILTERINPUT_HASHTAGS],
            [self::class, self::MODULE_FILTERINPUT_NAME],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_SEARCH => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_SEARCH],
            self::MODULE_FILTERINPUT_NAME => [UserFilterInputProcessor::class, UserFilterInputProcessor::FILTERINPUT_NAME],
            self::MODULE_FILTERINPUT_HASHTAGS => [PoP_Module_Processor_FormsFilterInputProcessor::class, PoP_Module_Processor_FormsFilterInputProcessor::FILTERINPUT_HASHTAGS],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::MODULE_FILTERINPUT_SEARCH:
    //         case self::MODULE_FILTERINPUT_HASHTAGS:
    //         case self::MODULE_FILTERINPUT_NAME:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_SEARCH:
                return TranslationAPIFacade::getInstance()->__('Search', 'pop-coreprocessors');

            case self::MODULE_FILTERINPUT_HASHTAGS:
                return TranslationAPIFacade::getInstance()->__('Hashtags', 'pop-coreprocessors');

            case self::MODULE_FILTERINPUT_NAME:
                return TranslationAPIFacade::getInstance()->__('Name', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_SEARCH:
            case self::MODULE_FILTERINPUT_HASHTAGS:
            case self::MODULE_FILTERINPUT_NAME:
                // Add a nice name, so that the URL params when filtering make sense
                $names = array(
                    self::MODULE_FILTERINPUT_SEARCH => 'searchfor',
                    self::MODULE_FILTERINPUT_HASHTAGS => 'tags',
                    self::MODULE_FILTERINPUT_NAME => 'nombre',
                );
                return $names[$component[1]];
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::MODULE_FILTERINPUT_SEARCH => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_HASHTAGS => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_NAME => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_SEARCH => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_HASHTAGS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_NAME => $translationAPI->__('', ''),
            default => null,
        };
    }
}



