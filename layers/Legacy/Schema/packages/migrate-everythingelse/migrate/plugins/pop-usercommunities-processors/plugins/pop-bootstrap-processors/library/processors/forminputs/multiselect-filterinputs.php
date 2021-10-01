<?php
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberPrivilegeEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberStatusEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberTagEnumTypeResolver;

class GD_URE_Module_Processor_ProfileMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES = 'filterinput-memberprivileges';
    public const MODULE_URE_FILTERINPUT_MEMBERTAGS = 'filterinput-membertags';
    public const MODULE_URE_FILTERINPUT_MEMBERSTATUS = 'filterinput-memberstatus';

    protected \PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver;
    protected \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver;

    public function autowireGD_URE_Module_Processor_ProfileMultiSelectFilterInputs(
        \PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver,
        \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES],
            [self::class, self::MODULE_URE_FILTERINPUT_MEMBERTAGS],
            [self::class, self::MODULE_URE_FILTERINPUT_MEMBERSTATUS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERINPUT_MEMBERPRIVILEGES],
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERINPUT_MEMBERTAGS],
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERINPUT_MEMBERSTATUS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
    //         case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
    //         case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
                return TranslationAPIFacade::getInstance()->__('Privileges', 'ure-popprocessors');

            case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
                return TranslationAPIFacade::getInstance()->__('Tags', 'ure-popprocessors');

            case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
                return TranslationAPIFacade::getInstance()->__('Status', 'ure-popprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
                return GD_URE_FormInput_FilterMemberPrivileges::class;

            case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
                return GD_URE_FormInput_FilterMemberTags::class;

            case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
                return GD_URE_FormInput_MultiMemberStatus::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
                return 'privileges';

            case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
                return 'tags';

            case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
                return 'status';
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputTypeResolver(array $module): \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => SchemaDefinition::TYPE_ENUM,
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => SchemaDefinition::TYPE_ENUM,
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => SchemaDefinition::TYPE_ENUM,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match($module[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => true,
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => true,
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }

    protected function modifyFilterSchemaDefinitionItems(array &$schemaDefinitionItems, array $module): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
                /**
                 * @var MemberPrivilegeEnumTypeResolver
                 */
                $memberPrivilegeEnumTypeResolver = $instanceManager->getInstance(MemberPrivilegeEnumTypeResolver::class);
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_NAME] = $memberPrivilegeEnumTypeResolver->getTypeName();
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $memberPrivilegeEnumTypeResolver
                );
                break;
            case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
                /**
                 * @var MemberTagEnumTypeResolver
                 */
                $memberTagEnumTypeResolver = $instanceManager->getInstance(MemberTagEnumTypeResolver::class);
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_NAME] = $memberTagEnumTypeResolver->getTypeName();
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $memberTagEnumTypeResolver
                );
                break;
            case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
                /**
                 * @var MemberStatusEnumTypeResolver
                 */
                $memberStatusEnumTypeResolver = $instanceManager->getInstance(MemberStatusEnumTypeResolver::class);
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_NAME] = $memberStatusEnumTypeResolver->getTypeName();
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $memberStatusEnumTypeResolver
                );
                break;
        }
    }
}



