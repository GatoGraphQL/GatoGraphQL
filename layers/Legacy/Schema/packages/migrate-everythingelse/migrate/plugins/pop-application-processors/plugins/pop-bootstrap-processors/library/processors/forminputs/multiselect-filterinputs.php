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

    public function getComponentsToProcess(): array
    {
        return array(
            [self::class, self::COMPONENT_FILTERINPUT_APPLIESTO],
            [self::class, self::COMPONENT_FILTERINPUT_CATEGORIES],
            [self::class, self::COMPONENT_FILTERINPUT_CONTENTSECTIONS],
            [self::class, self::COMPONENT_FILTERINPUT_POSTSECTIONS],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_POSTSECTIONS => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_POSTSECTIONS],
            self::COMPONENT_FILTERINPUT_CATEGORIES => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_CATEGORIES],
            self::COMPONENT_FILTERINPUT_CONTENTSECTIONS => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_CONTENTSECTIONS],
            self::COMPONENT_FILTERINPUT_APPLIESTO => [PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::class, PoP_Module_Processor_CRUDMultiSelectFilterInputProcessor::FILTERINPUT_APPLIESTO],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_FILTERINPUT_CATEGORIES:
    //         case self::COMPONENT_FILTERINPUT_CONTENTSECTIONS:
    //         case self::COMPONENT_FILTERINPUT_POSTSECTIONS:
    //         case self::COMPONENT_FILTERINPUT_APPLIESTO:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_CONTENTSECTIONS:
            case self::COMPONENT_FILTERINPUT_POSTSECTIONS:
                return TranslationAPIFacade::getInstance()->__('Sections', 'poptheme-wassup');

            case self::COMPONENT_FILTERINPUT_APPLIESTO:
                return TranslationAPIFacade::getInstance()->__('Applies to', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_CATEGORIES:
                return GD_FormInput_Categories::class;

            case self::COMPONENT_FILTERINPUT_CONTENTSECTIONS:
                return GD_FormInput_ContentSections::class;

            case self::COMPONENT_FILTERINPUT_POSTSECTIONS:
                return GD_FormInput_PostSections::class;

            case self::COMPONENT_FILTERINPUT_APPLIESTO:
                return GD_FormInput_AppliesTo::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_CATEGORIES:
                return 'appliesto';

            case self::COMPONENT_FILTERINPUT_CONTENTSECTIONS:
                return 'categories';

            case self::COMPONENT_FILTERINPUT_POSTSECTIONS:
            case self::COMPONENT_FILTERINPUT_APPLIESTO:
                return 'sections';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::COMPONENT_FILTERINPUT_APPLIESTO => $this->stringScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_CATEGORIES => $this->idScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_CONTENTSECTIONS => $this->idScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_POSTSECTIONS => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match($component[1]) {
            self::COMPONENT_FILTERINPUT_APPLIESTO,
            self::COMPONENT_FILTERINPUT_CATEGORIES,
            self::COMPONENT_FILTERINPUT_CONTENTSECTIONS,
            self::COMPONENT_FILTERINPUT_POSTSECTIONS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_APPLIESTO => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_CATEGORIES => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_CONTENTSECTIONS => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_POSTSECTIONS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



