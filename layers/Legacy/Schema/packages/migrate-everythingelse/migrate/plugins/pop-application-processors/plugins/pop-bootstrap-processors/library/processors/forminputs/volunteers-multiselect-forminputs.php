<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_BooleanMultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT = 'filterinput-volunteersneededmulti';

    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT],
        );
    }

    /**
     * @todo Migrate from [FilterInput::class, FilterInput::NAME] to FilterInputInterface
     */
    public function getFilterInput(\PoP\ComponentModel\Component\Component $component): ?FilterInputInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => [PoP_Module_Processor_CRUDMultiSelectFilterInput::class, PoP_Module_Processor_CRUDMultiSelectFilterInput::FILTERINPUT_VOLUNTEERSNEEDED],
            default => null,
        };
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return TranslationAPIFacade::getInstance()->__('Volunteers Needed?', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return GD_FormInput_MultiYesNo::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return 'volunteersneeded';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(\PoP\ComponentModel\Component\Component $component): InputTypeResolverInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => $this->booleanScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(\PoP\ComponentModel\Component\Component $component): int
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => $translationAPI->__('', ''),
            default => null,
        };
    }
}



