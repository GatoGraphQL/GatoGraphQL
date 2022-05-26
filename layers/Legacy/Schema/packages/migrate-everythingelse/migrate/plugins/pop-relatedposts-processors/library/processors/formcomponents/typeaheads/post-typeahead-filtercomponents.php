<?php

use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_PostSelectableTypeaheadFilterComponents extends PoP_Module_Processor_PostSelectableTypeaheadFormComponentsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES = 'filtercomponent-selectabletypeahead-references';

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
            [self::class, self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
        );
    }

    /**
     * @todo Migrate from [FilterInput::class, FilterInput::NAME] to FilterInputInterface
     */
    public function getFilterInput(array $component): ?FilterInputInterface
    {
        return match($component[1]) {
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES => [PoP_Module_Processor_ReferencesFilterInput::class, PoP_Module_Processor_ReferencesFilterInput::FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES],
            default => null,
        };
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getInputSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::COMPONENT_FORMINPUT_TEXT_TYPEAHEADRELATEDCONTENT];
        }

        return parent::getInputSubcomponent($component);
    }

    public function getComponentSubcomponents(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return array(
                    [PoP_Module_Processor_PostTypeaheadComponentFormInputs::class, PoP_Module_Processor_PostTypeaheadComponentFormInputs::COMPONENT_TYPEAHEAD_COMPONENT_CONTENT],
                );
        }

        return parent::getComponentSubcomponents($component);
    }

    public function getTriggerLayoutSubcomponent(array $component)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
                return [PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_PostSelectableTypeaheadTriggerFormComponents::COMPONENT_FORMCOMPONENT_SELECTABLETYPEAHEADTRIGGER_REFERENCES];
        }

        return parent::getTriggerLayoutSubcomponent($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match($component[1]) {
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::COMPONENT_FILTERCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES => $translationAPI->__('', ''),
            default => null,
        };
    }
}



