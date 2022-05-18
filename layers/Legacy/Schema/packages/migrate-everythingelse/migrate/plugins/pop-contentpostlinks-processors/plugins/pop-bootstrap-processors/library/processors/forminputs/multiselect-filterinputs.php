<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_FILTERINPUT_LINKCATEGORIES = 'filterinput-linkcategories';
    public final const MODULE_FILTERINPUT_LINKACCESS = 'filterinput-linkaccess';

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

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_LINKCATEGORIES],
            [self::class, self::MODULE_FILTERINPUT_LINKACCESS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_LINKCATEGORIES => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputProcessor::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputProcessor::FILTERINPUT_LINKCATEGORIES],
            self::MODULE_FILTERINPUT_LINKACCESS => [PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputProcessor::class, PoP_ContentPostLinksCreation_Module_Processor_CreateUpdatePostMultiSelectFilterInputProcessor::FILTERINPUT_LINKACCESS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_LINKCATEGORIES:
    //         case self::MODULE_FILTERINPUT_LINKACCESS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKCATEGORIES:
                return TranslationAPIFacade::getInstance()->__('Categories', 'poptheme-wassup');

            case self::MODULE_FILTERINPUT_LINKACCESS:
                return TranslationAPIFacade::getInstance()->__('Access type', 'poptheme-wassup');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKCATEGORIES:
                return GD_FormInput_LinkCategories::class;

            case self::MODULE_FILTERINPUT_LINKACCESS:
                return GD_FormInput_LinkAccess::class;
        }

        return parent::getInputClass($module);
    }

    public function getDbobjectField(array $module): ?string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKACCESS:
                return 'linkaccess';
        }

        return parent::getDbobjectField($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_LINKCATEGORIES:
                return 'categories';

            case self::MODULE_FILTERINPUT_LINKACCESS:
                return 'access';
        }

        return parent::getName($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_LINKCATEGORIES => $this->idScalarTypeResolver,
            self::MODULE_FILTERINPUT_LINKACCESS => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_LINKCATEGORIES => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_FILTERINPUT_LINKCATEGORIES => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_LINKACCESS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



