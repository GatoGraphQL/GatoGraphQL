<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_CreateUpdatePostMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERINPUT_APPLIESTO = 'filterinput-appliesto';
    public final const MODULE_FILTERINPUT_CATEGORIES = 'filterinput-categories';
    public final const MODULE_FILTERINPUT_CONTENTSECTIONS = 'filterinput-contentsections';
    public final const MODULE_FILTERINPUT_POSTSECTIONS = 'filterinput-postsections';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_APPLIESTO],
            [self::class, self::MODULE_FILTERINPUT_CATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_CONTENTSECTIONS],
            [self::class, self::MODULE_FILTERINPUT_POSTSECTIONS],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_POSTSECTIONS => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_POSTSECTIONS],
            self::MODULE_FILTERINPUT_CATEGORIES => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_CATEGORIES],
            self::MODULE_FILTERINPUT_CONTENTSECTIONS => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_CONTENTSECTIONS],
            self::MODULE_FILTERINPUT_APPLIESTO => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_APPLIESTO],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_FILTERINPUT_CATEGORIES:
    //         case self::MODULE_FILTERINPUT_CONTENTSECTIONS:
    //         case self::MODULE_FILTERINPUT_POSTSECTIONS:
    //         case self::MODULE_FILTERINPUT_APPLIESTO:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($componentVariation);
    // }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_CONTENTSECTIONS:
            case self::MODULE_FILTERINPUT_POSTSECTIONS:
                return TranslationAPIFacade::getInstance()->__('Sections', 'poptheme-wassup');

            case self::MODULE_FILTERINPUT_APPLIESTO:
                return TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::MODULE_FILTERINPUT_CONTENTSECTIONS:
                return GD_FormInput_ContentSections::class;

            case self::MODULE_FILTERINPUT_POSTSECTIONS:
                return GD_FormInput_PostSections::class;

            case self::MODULE_FILTERINPUT_APPLIESTO:
                return GD_FormInput_AppliesTo::class;
        }

        return parent::getInputClass($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_FILTERINPUT_CATEGORIES:
                return 'appliesto';

            case self::MODULE_FILTERINPUT_CONTENTSECTIONS:
                return 'categories';

            case self::MODULE_FILTERINPUT_POSTSECTIONS:
            case self::MODULE_FILTERINPUT_APPLIESTO:
                return 'sections';
        }

        return parent::getName($componentVariation);
    }

    public function getFilterInputTypeResolver(array $componentVariation): InputTypeResolverInterface
    {
        return match($componentVariation[1]) {
            self::MODULE_FILTERINPUT_APPLIESTO => $this->stringScalarTypeResolver,
            self::MODULE_FILTERINPUT_CATEGORIES => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_CONTENTSECTIONS => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_POSTSECTIONS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match($componentVariation[1]) {
            self::MODULE_FILTERINPUT_APPLIESTO,
            self::MODULE_FILTERINPUT_CATEGORIES,
            self::MODULE_FILTERINPUT_CONTENTSECTIONS,
            self::MODULE_FILTERINPUT_POSTSECTIONS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($componentVariation[1]) {
            self::MODULE_FILTERINPUT_APPLIESTO => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_CATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_CONTENTSECTIONS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_POSTSECTIONS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



