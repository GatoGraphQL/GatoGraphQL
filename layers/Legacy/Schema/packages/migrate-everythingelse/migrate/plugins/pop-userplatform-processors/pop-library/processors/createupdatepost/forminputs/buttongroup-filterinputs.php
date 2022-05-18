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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            [self::class, self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_CATEGORIES],
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS],
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => [PoP_Module_Processor_UserPlatformFilterInputProcessor::class, PoP_Module_Processor_UserPlatformFilterInputProcessor::FILTERINPUT_BUTTONGROUP_POSTSECTIONS],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
    //         case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($componentVariation);
    // }

    public function getName(array $componentVariation): string
    {
        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                // Return the name of the other Categories input (the multiselect), so we can filter by this input using the DelegatorFilter pretending to be the other one
                $inputs = array(
                    self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CATEGORIES],
                    self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_CONTENTSECTIONS],
                    self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => [PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::class, PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs::MODULE_FILTERINPUT_POSTSECTIONS],
                );
                $input = $inputs[$componentVariation[1]];
                return $componentprocessor_manager->getProcessor($input)->getName($input);
        }

        return parent::getName($componentVariation);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
                return GD_FormInput_ContentSections::class;

            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return GD_FormInput_PostSections::class;
        }

        return parent::getInputClass($componentVariation);
    }

    public function isMultiple(array $componentVariation): bool
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS:
                return true;
        }

        return parent::isMultiple($componentVariation);
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match($componentVariation[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match($componentVariation[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES,
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS,
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_BUTTONGROUP_CATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_BUTTONGROUP_CONTENTSECTIONS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_BUTTONGROUP_POSTSECTIONS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



