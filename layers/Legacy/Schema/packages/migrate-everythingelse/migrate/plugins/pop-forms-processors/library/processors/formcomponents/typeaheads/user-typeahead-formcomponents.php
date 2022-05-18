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

    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES = 'filtercomponent-selectabletypeahead-profiles';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => [PoP_Module_Processor_FormsFilterInputProcessor::class, PoP_Module_Processor_FormsFilterInputProcessor::FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return TranslationAPIFacade::getInstance()->__('Authors', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADPROFILES];
        }

        return parent::getInputSubmodule($component);
    }

    public function getComponentSubmodules(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                // Allow PoP Common User Roles to change this
                return \PoP\Root\App::applyFilters(
                    'UserSelectableTypeaheadFormInputs:components:profiles',
                    array(
                        [PoP_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_Module_Processor_UserTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_USERS],
                    )
                );
        }

        return parent::getComponentSubmodules($component);
    }

    public function getTriggerLayoutSubmodule(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return [PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES];
        }

        return parent::getTriggerLayoutSubmodule($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                // Calling it either 'authors' or 'users' for some reason doesn't work!
                return 'profiles';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match($component[1]) {
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => $translationAPI->__('', ''),
            default => null,
        };
    }
}



