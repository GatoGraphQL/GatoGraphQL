<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoPTheme_Wassup_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_BooleanMultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT = 'filterinput-volunteersneededmulti';

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
            [self::class, self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_VOLUNTEERSNEEDED],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return TranslationAPIFacade::getInstance()->__('Volunteers Needed?', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return GD_FormInput_MultiYesNo::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT:
                return 'volunteersneeded';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => $this->booleanScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match($component[1]) {
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_VOLUNTEERSNEEDED_MULTISELECT => $translationAPI->__('', ''),
            default => null,
        };
    }
}



