<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_CreateUpdatePostButtonGroupFilterInputs extends PoP_Module_Processor_ButtonGroupFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES = 'filterinput-buttongroup-categories';
    public final const MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS = 'filterinput-buttongroup-contentsections';
    public final const MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS = 'filterinput-buttongroup-postsections';

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
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_CATEGORIES],
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getName(array $component): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                // Return the name of the other Categories input (the multiselect), so we can filter by this input using the DelegatorFilter pretending to be the other one
                $inputs = array(
                    self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                    self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CONTENTSECTIONS],
                    self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_POSTSECTIONS],
                );
                $input = $inputs[$component[1]];
                return $componentprocessor_manager->getProcessor($input)->getName($input);
        }

        return parent::getName($component);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
                return GD_FormInput_ContentSections::class;

            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return GD_FormInput_PostSections::class;
        }

        return parent::getInputClass($component);
    }

    public function isMultiple(array $component): bool
    {
        switch ($component[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match($component[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES,
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS,
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



