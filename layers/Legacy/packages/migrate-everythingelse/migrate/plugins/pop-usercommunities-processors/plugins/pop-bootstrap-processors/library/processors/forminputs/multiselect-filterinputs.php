<?php
use PoPSchema\EverythingElse\Enums\MemberTagEnum;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoPSchema\EverythingElse\Enums\MemberStatusEnum;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\EverythingElse\Enums\MemberPrivilegeEnum;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;

class GD_URE_Module_Processor_ProfileMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES = 'filterinput-memberprivileges';
    public const MODULE_URE_FILTERINPUT_MEMBERTAGS = 'filterinput-membertags';
    public const MODULE_URE_FILTERINPUT_MEMBERSTATUS = 'filterinput-memberstatus';

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

    public function getInputClass(array $module)
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

    public function getName(array $module)
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

    public function getSchemaFilterInputType(array $module): string
    {
        return match($module[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => SchemaDefinition::TYPE_ENUM,
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => SchemaDefinition::TYPE_ENUM,
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => SchemaDefinition::TYPE_ENUM,
            default => $this->getDefaultSchemaFilterInputType(),
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
                 * @var MemberPrivilegeEnum
                 */
                $memberPrivilegeEnum = $instanceManager->getInstance(MemberPrivilegeEnum::class);
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_NAME] = $memberPrivilegeEnum->getName();
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $memberPrivilegeEnum->getValues()
                );
                break;
            case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
                /**
                 * @var MemberTagEnum
                 */
                $memberTagEnum = $instanceManager->getInstance(MemberTagEnum::class);
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_NAME] = $memberTagEnum->getName();
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $memberTagEnum->getValues()
                );
                break;
            case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
                /**
                 * @var MemberStatusEnum
                 */
                $memberStatusEnum = $instanceManager->getInstance(MemberStatusEnum::class);
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_NAME] = $memberStatusEnum->getName();
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $memberStatusEnum->getValues()
                );
                break;
        }
    }
}



