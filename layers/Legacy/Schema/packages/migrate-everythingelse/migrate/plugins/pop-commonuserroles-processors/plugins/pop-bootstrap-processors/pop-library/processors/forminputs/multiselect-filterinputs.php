<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class GD_URE_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS = 'filterinput-individualinterests';
    public final const COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES = 'filterinput-organizationcategories';
    public final const COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES = 'filterinput-organizationtypes';

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
            [self::class, self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS],
            [self::class, self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            [self::class, self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES],
        );
    }

    /**
     * @todo Migrate from [FilterInput::class, FilterInput::NAME] to FilterInputInterface
     */
    public function getFilterInput(\PoP\ComponentModel\Component\Component $component): ?FilterInputInterface
    {
        return match($component->name) {
            self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS => [GD_URE_Module_Processor_MultiSelectFilterInput::class, GD_URE_Module_Processor_MultiSelectFilterInput::URE_FILTERINPUT_INDIVIDUALINTERESTS],
            self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => [GD_URE_Module_Processor_MultiSelectFilterInput::class, GD_URE_Module_Processor_MultiSelectFilterInput::URE_FILTERINPUT_ORGANIZATIONCATEGORIES],
            self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES => [GD_URE_Module_Processor_MultiSelectFilterInput::class, GD_URE_Module_Processor_MultiSelectFilterInput::URE_FILTERINPUT_ORGANIZATIONTYPES],
            default => null,
        };
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS:
    //         case self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
    //         case self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return TranslationAPIFacade::getInstance()->__('Interests', 'poptheme-wassup');

            case self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                // Allow AgendaUrbana to Override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:categories',
                    TranslationAPIFacade::getInstance()->__('Organization Categories', 'poptheme-wassup')
                );

            case self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES:
                // Allow AgendaUrbana to Override
                return \PoP\Root\App::applyFilters(
                    'GD_URE_Module_Processor_MultiSelectFormInputs:label:types',
                    TranslationAPIFacade::getInstance()->__('Organization Types', 'poptheme-wassup')
                );
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return GD_FormInput_IndividualInterests::class;

            case self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                return GD_FormInput_OrganizationCategories::class;

            case self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES:
                return GD_FormInput_OrganizationTypes::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS:
                return 'interests';

            case self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES:
                return 'categories';

            case self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES:
                return 'types';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(\PoP\ComponentModel\Component\Component $component): InputTypeResolverInterface
    {
        return match($component->name) {
            self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS => $this->stringScalarTypeResolver,
            self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => $this->stringScalarTypeResolver,
            self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(\PoP\ComponentModel\Component\Component $component): int
    {
        return match($component->name) {
            self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS,
            self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES,
            self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component->name) {
            self::COMPONENT_URE_FILTERINPUT_INDIVIDUALINTERESTS => $translationAPI->__('', ''),
            self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONCATEGORIES => $translationAPI->__('', ''),
            self::COMPONENT_URE_FILTERINPUT_ORGANIZATIONTYPES => $translationAPI->__('', ''),
            default => null,
        };
    }
}



