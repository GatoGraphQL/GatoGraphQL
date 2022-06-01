<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES = 'filterinput-buttongroup-categories';
    public final const COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS = 'filterinput-buttongroup-contentsections';
    public final const COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS = 'filterinput-buttongroup-postsections';

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
            [self::class, self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            [self::class, self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    /**
     * @todo Migrate from [FilterInput::class, FilterInput::NAME] to FilterInputInterface
     */
    public function getFilterInput(\PoP\ComponentModel\Component\Component $component): ?FilterInputInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES => [PoP_Module_Processor_UserPlatformFilterInput::class, PoP_Module_Processor_UserPlatformFilterInput::FILTERINPUT_BUTTONGROUP_CATEGORIES],
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => [PoP_Module_Processor_UserPlatformFilterInput::class, PoP_Module_Processor_UserPlatformFilterInput::FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => [PoP_Module_Processor_UserPlatformFilterInput::class, PoP_Module_Processor_UserPlatformFilterInput::FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
            default => null,
        };
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES:
    //         case self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
    //         case self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                // Return the name of the other Categories input (the multiselect), so we can filter by this input using the DelegatorFilter pretending to be the other one
                $inputs = array(
                    self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_CATEGORIES],
                    self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_CONTENTSECTIONS],
                    self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::COMPONENT_FILTERINPUT_POSTSECTIONS],
                );
                $input = $inputs[$component->name];
                return $componentprocessor_manager->getProcessor($input)->getName($input);
        }

        return parent::getName($component);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
                return GD_FormInput_ContentSections::class;

            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return GD_FormInput_PostSections::class;
        }

        return parent::getInputClass($component);
    }

    public function isMultiple(\PoP\ComponentModel\Component\Component $component): bool
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($component);
    }

    public function getFilterInputTypeResolver(\PoP\ComponentModel\Component\Component $component): InputTypeResolverInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES => $this->idScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $this->idScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(\PoP\ComponentModel\Component\Component $component): int
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES,
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS,
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_CATEGORIES => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



