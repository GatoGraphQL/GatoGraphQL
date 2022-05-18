<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberPrivilegeEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberStatusEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberTagEnumTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

class GD_URE_Module_Processor_ProfileMultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES = 'filterinput-memberprivileges';
    public final const MODULE_URE_FILTERINPUT_MEMBERTAGS = 'filterinput-membertags';
    public final const MODULE_URE_FILTERINPUT_MEMBERSTATUS = 'filterinput-memberstatus';

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

    public function getComponentVariationsToProcess(): array
    {
        return array(
            [self::class, self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES],
            [self::class, self::MODULE_URE_FILTERINPUT_MEMBERTAGS],
            [self::class, self::MODULE_URE_FILTERINPUT_MEMBERSTATUS],
        );
    }

    public function getFilterInput(array $componentVariation): ?array
    {
        $filterInputs = [
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERINPUT_MEMBERPRIVILEGES],
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERINPUT_MEMBERTAGS],
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => [GD_URE_Module_Processor_FilterInputProcessor::class, GD_URE_Module_Processor_FilterInputProcessor::URE_FILTERINPUT_MEMBERSTATUS],
        ];
        return $filterInputs[$componentVariation[1]] ?? null;
    }

    // public function isFiltercomponent(array $componentVariation)
    // {
    //     switch ($componentVariation[1]) {
    //         case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
    //         case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
    //         case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($componentVariation);
    // }

    public function getLabelText(array $componentVariation, array &$props)
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
                return TranslationAPIFacade::getInstance()->__('Privileges', 'ure-popprocessors');

            case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
                return TranslationAPIFacade::getInstance()->__('Tags', 'ure-popprocessors');

            case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
                return TranslationAPIFacade::getInstance()->__('Status', 'ure-popprocessors');
        }

        return parent::getLabelText($componentVariation, $props);
    }

    public function getInputClass(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
                return GD_URE_FormInput_FilterMemberPrivileges::class;

            case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
                return GD_URE_FormInput_FilterMemberTags::class;

            case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
                return GD_URE_FormInput_MultiMemberStatus::class;
        }

        return parent::getInputClass($componentVariation);
    }

    public function getName(array $componentVariation): string
    {
        switch ($componentVariation[1]) {
            case self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES:
                return 'privileges';

            case self::MODULE_URE_FILTERINPUT_MEMBERTAGS:
                return 'tags';

            case self::MODULE_URE_FILTERINPUT_MEMBERSTATUS:
                return 'status';
        }

        return parent::getName($componentVariation);
    }

    public function getFilterInputTypeResolver(array $componentVariation): \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface
    {
        return match($componentVariation[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => $this->memberPrivilegeEnumTypeResolver,
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => $this->memberTagEnumTypeResolver,
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => $this->memberStatusEnumTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $componentVariation): int
    {
        return match($componentVariation[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES,
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS,
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $componentVariation): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($componentVariation[1]) {
            self::MODULE_URE_FILTERINPUT_MEMBERPRIVILEGES => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_MEMBERTAGS => $translationAPI->__('', ''),
            self::MODULE_URE_FILTERINPUT_MEMBERSTATUS => $translationAPI->__('', ''),
            default => null,
        };
    }
}



