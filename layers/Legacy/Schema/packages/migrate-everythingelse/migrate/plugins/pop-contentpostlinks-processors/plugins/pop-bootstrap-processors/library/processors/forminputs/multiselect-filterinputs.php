<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERINPUT_LINKCATEGORIES = 'filterinput-linkcategories';
    public final const COMPONENT_FILTERINPUT_LINKACCESS = 'filterinput-linkaccess';

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

    /**
     * @return string[]
     */
    public function getComponentNamesToProcess(): array
    {
        return array(
            self::COMPONENT_FILTERINPUT_LINKCATEGORIES,
            self::COMPONENT_FILTERINPUT_LINKACCESS,
        );
    }

    /**
     * @todo Migrate from [FilterInput::class, FilterInput::NAME] to FilterInputInterface
     */
    public function getFilterInput(\PoP\ComponentModel\Component\Component $component): ?FilterInputInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_LINKCATEGORIES => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInput::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInput::FILTERINPUT_LINKCATEGORIES],
            self::COMPONENT_FILTERINPUT_LINKACCESS => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInput::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInput::FILTERINPUT_LINKACCESS],
            default => null,
        };
    }

    // public function isFiltercomponent(\PoP\ComponentModel\Component\Component $component)
    // {
    //     switch ($component->name) {
    //         case self::COMPONENT_FILTERINPUT_LINKCATEGORIES:
    //         case self::COMPONENT_FILTERINPUT_LINKACCESS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_LINKCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup');

            case self::COMPONENT_FILTERINPUT_LINKACCESS:
                return TranslationAPIFacade::getInstance()->__('Access type', 'poptheme-wassup');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_LINKCATEGORIES:
                return GD_FormInput_LinkCategories::class;

            case self::COMPONENT_FILTERINPUT_LINKACCESS:
                return GD_FormInput_LinkAccess::class;
        }

        return parent::getInputClass($component);
    }

    public function getDbobjectField(\PoP\ComponentModel\Component\Component $component): ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_LINKACCESS:
                return 'linkaccess';
        }

        return parent::getDbobjectField($component);
    }

    public function getName(\PoP\ComponentModel\Component\Component $component): string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_LINKCATEGORIES:
                return 'categories';

            case self::COMPONENT_FILTERINPUT_LINKACCESS:
                return 'access';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(\PoP\ComponentModel\Component\Component $component): InputTypeResolverInterface
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_LINKCATEGORIES => $this->idScalarTypeResolver,
            self::COMPONENT_FILTERINPUT_LINKACCESS => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(\PoP\ComponentModel\Component\Component $component): int
    {
        return match($component->name) {
            self::COMPONENT_FILTERINPUT_LINKCATEGORIES => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(\PoP\ComponentModel\Component\Component $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component->name) {
            self::COMPONENT_FILTERINPUT_LINKCATEGORIES => $translationAPI->__('', ''),
            self::COMPONENT_FILTERINPUT_LINKACCESS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



