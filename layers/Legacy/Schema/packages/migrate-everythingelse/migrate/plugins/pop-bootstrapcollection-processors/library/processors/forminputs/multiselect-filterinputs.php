<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\CustomPostModeratedStatusEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\CustomPostUnmoderatedStatusEnumTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS = 'filterinput-moderatedpoststatus';
    public final const COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS = 'filterinput-unmoderatedpoststatus';

    private ?CustomPostModeratedStatusEnumTypeResolver $customPostModeratedStatusEnumTypeResolver = null;
    private ?CustomPostUnmoderatedStatusEnumTypeResolver $customPostUnmoderatedStatusEnumTypeResolver = null;

    final public function setCustomPostModeratedStatusEnumTypeResolver(CustomPostModeratedStatusEnumTypeResolver $customPostModeratedStatusEnumTypeResolver): void
    {
        $this->customPostModeratedStatusEnumTypeResolver = $customPostModeratedStatusEnumTypeResolver;
    }
    final protected function getCustomPostModeratedStatusEnumTypeResolver(): CustomPostModeratedStatusEnumTypeResolver
    {
        return $this->customPostModeratedStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostModeratedStatusEnumTypeResolver::class);
    }
    final public function setCustomPostUnmoderatedStatusEnumTypeResolver(CustomPostUnmoderatedStatusEnumTypeResolver $customPostUnmoderatedStatusEnumTypeResolver): void
    {
        $this->customPostUnmoderatedStatusEnumTypeResolver = $customPostUnmoderatedStatusEnumTypeResolver;
    }
    final protected function getCustomPostUnmoderatedStatusEnumTypeResolver(): CustomPostUnmoderatedStatusEnumTypeResolver
    {
        return $this->customPostUnmoderatedStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostUnmoderatedStatusEnumTypeResolver::class);
    }

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS],
            [self::class, self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS => [PoP_Module_Processor_MultiSelectFilterInputProcessor::class, PoP_Module_Processor_MultiSelectFilterInputProcessor::FILTERINPUT_MODERATEDPOSTSTATUS],
            self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS => [PoP_Module_Processor_MultiSelectFilterInputProcessor::class, PoP_Module_Processor_MultiSelectFilterInputProcessor::FILTERINPUT_UNMODERATEDPOSTSTATUS],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS:
    //         case self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS:
            case self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS:
                return TranslationAPIFacade::getInstance()->__('Status', 'pop-coreprocessors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS:
                return GD_FormInput_ModeratedStatus::class;

            case self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS:
                return GD_FormInput_UnmoderatedStatus::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS:
            case self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS:
                // Add a nice name, so that the URL params when filtering make sense
                return 'status';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS => $this->customPostModeratedStatusEnumTypeResolver,
            self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS => $this->customPostUnmoderatedStatusEnumTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match($component[1]) {
            self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS,
            self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_MODERATEDPOSTSTATUS => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_UNMODERATEDPOSTSTATUS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



