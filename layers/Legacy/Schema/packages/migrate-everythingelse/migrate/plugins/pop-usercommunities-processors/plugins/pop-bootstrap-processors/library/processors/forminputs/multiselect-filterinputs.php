<?php
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberPrivilegeEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberStatusEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberTagEnumTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class GD_URE_Module_Processor_ProfileMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES = 'filterinput-memberprivileges';
    public const MODULE_URE_FILTERINPUT_MEMBERTAGS = 'filterinput-membertags';
    public const MODULE_URE_FILTERINPUT_MEMBERSTATUS = 'filterinput-memberstatus';

    private ?MemberPrivilegeEnumTypeResolver $memberPrivilegeEnumTypeResolver = null;
    private ?MemberTagEnumTypeResolver $memberTagEnumTypeResolver = null;
    private ?MemberStatusEnumTypeResolver $memberStatusEnumTypeResolver = null;

    final public function setMemberPrivilegeEnumTypeResolver(MemberPrivilegeEnumTypeResolver $memberPrivilegeEnumTypeResolver): void
    {
        $this->memberPrivilegeEnumTypeResolver = $memberPrivilegeEnumTypeResolver;
    }
    final protected function getMemberPrivilegeEnumTypeResolver(): MemberPrivilegeEnumTypeResolver
    {
        return $this->memberPrivilegeEnumTypeResolver ??= $this->instanceManager->getInstance(MemberPrivilegeEnumTypeResolver::class);
    }
    final public function setMemberTagEnumTypeResolver(MemberTagEnumTypeResolver $memberTagEnumTypeResolver): void
    {
        $this->memberTagEnumTypeResolver = $memberTagEnumTypeResolver;
    }
    final protected function getMemberTagEnumTypeResolver(): MemberTagEnumTypeResolver
    {
        return $this->memberTagEnumTypeResolver ??= $this->instanceManager->getInstance(MemberTagEnumTypeResolver::class);
    }
    final public function setMemberStatusEnumTypeResolver(MemberStatusEnumTypeResolver $memberStatusEnumTypeResolver): void
    {
        $this->memberStatusEnumTypeResolver = $memberStatusEnumTypeResolver;
    }
    final protected function getMemberStatusEnumTypeResolver(): MemberStatusEnumTypeResolver
    {
        return $this->memberStatusEnumTypeResolver ??= $this->instanceManager->getInstance(MemberStatusEnumTypeResolver::class);
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

    public function getFilterInputTypeResolver(array $module): \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => $this->memberPrivilegeEnumTypeResolver,
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => $this->memberTagEnumTypeResolver,
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => $this->memberStatusEnumTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match($module[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES,
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS,
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



