<?php
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\EverythingElse\Enums\CustomPostModeratedStatusEnum;
use PoPSchema\EverythingElse\Enums\CustomPostUnmoderatedStatusEnum;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;

class PoP_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface, DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERINPUT_MODERATEDPOSTSTATUS = 'filterinput-moderatedpoststatus';
    public const MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS = 'filterinput-unmoderatedpoststatus';

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS],
            [self::class, self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS => [PoP_Module_Processor_MultiSelectFilterInputProcessor::class, PoP_Module_Processor_MultiSelectFilterInputProcessor::FILTERINPUT_MODERATEDPOSTSTATUS],
            self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS => [PoP_Module_Processor_MultiSelectFilterInputProcessor::class, PoP_Module_Processor_MultiSelectFilterInputProcessor::FILTERINPUT_UNMODERATEDPOSTSTATUS],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS:
    //         case self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS:
            case self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS:
                return TranslationAPIFacade::getInstance()->__('Status', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputClass(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS:
                return GD_FormInput_ModeratedStatus::class;

            case self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS:
                return GD_FormInput_UnmoderatedStatus::class;
        }

        return parent::getInputClass($module);
    }

    public function getName(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS:
            case self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS:
                // Add a nice name, so that the URL params when filtering make sense
                return 'status';
        }

        return parent::getName($module);
    }

    public function getSchemaFilterInputType(array $module): string
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS => SchemaDefinition::TYPE_ENUM,
            self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS => SchemaDefinition::TYPE_ENUM,
            default => $this->getDefaultSchemaFilterInputType(),
        };
    }

    public function getSchemaFilterInputIsArrayType(array $module): bool
    {
        return match($module[1]) {
            self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS => true,
            self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS => true,
            default => false,
        };
    }

    public function getSchemaFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS => $translationAPI->__('', ''),
            self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS => $translationAPI->__('', ''),
        ];
        return $descriptions[$module[1]] ?? null;
    }

    protected function modifyFilterSchemaDefinitionItems(array &$schemaDefinitionItems, array $module): void
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($module[1]) {
            case self::MODULE_FILTERINPUT_MODERATEDPOSTSTATUS:
                /**
                 * @var CustomPostModeratedStatusEnum
                 */
                $customPostModeratedStatusEnum = $instanceManager->getInstance(CustomPostModeratedStatusEnum::class);
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_NAME] = $customPostModeratedStatusEnum->getName();
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $customPostModeratedStatusEnum->getValues()
                );
                break;
            case self::MODULE_FILTERINPUT_UNMODERATEDPOSTSTATUS:
                /**
                 * @var CustomPostUnmoderatedStatusEnum
                 */
                $customPostUnmoderatedStatusEnum = $instanceManager->getInstance(CustomPostUnmoderatedStatusEnum::class);
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_NAME] = $customPostUnmoderatedStatusEnum->getName();
                $schemaDefinitionItems[SchemaDefinition::ARGNAME_ENUM_VALUES] = SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $customPostUnmoderatedStatusEnum->getValues()
                );
                break;
        }
    }
}



