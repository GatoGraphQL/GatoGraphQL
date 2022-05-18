<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_UserSelectableTypeaheadFilterInputs extends PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES = 'filtercomponent-selectabletypeahead-profiles';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => [PoP_Module_Processor_FormsFilterInputProcessor::class, PoP_Module_Processor_FormsFilterInputProcessor::FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($componentVariation);
    // }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return TranslationAPIFacade::getInstance()->__('Authors', 'pop-coreprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADPROFILES];
        }

        return parent::getInputSubmodule($componentVariation);
    }

    public function getComponentSubmodules(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                // Allow PoP Common User Roles to change this
                return \PoP\Root\App::applyFilters(
                    'UserSelectableTypeaheadFormInputs:components:profiles',
                    array(
                        [PoP_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_USERS],
                    )
                );
        }

        return parent::getComponentSubmodules($componentVariation);
    }

    public function getTriggerLayoutSubmodule(array $componentVariation)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return [PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES];
        }

        return parent::getTriggerLayoutSubmodule($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                // Calling it either 'authors' or 'users' for some reason doesn't work!
                return 'profiles';
        }

        return parent::getName($componentVariation);
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match($componentVariation[1]) {
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match($componentVariation[1]) {
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($componentVariation[1]) {
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => $translationAPI->__('', ''),
            default => null,
        };
    }
}



