<?php
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class UserStance_URE_Module_Processor_MultiSelectFilterInputs extends PoP_Module_Processor_MultiSelectFormInputsBase implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputComponentProcessorTrait;

    public final const COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT = 'filterinput-multiselect-authorrole';

    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

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
            [self::class, self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT],
        );
    }

    public function getFilterInput(array $component): ?array
    {
        $filterInputs = [
            self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT => [PoP_Module_Processor_UserStanceUserRolesFilterInputProcessor::class, PoP_Module_Processor_UserStanceUserRolesFilterInputProcessor::FILTERINPUT_AUTHORROLE_MULTISELECT],
        ];
        return $filterInputs[$component[1]] ?? null;
    }

    // public function isFiltercomponent(array $component)
    // {
    //     switch ($component[1]) {
    //         case self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($component);
    // }

    public function getLabelText(array $component, array &$props)
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT:
                return TranslationAPIFacade::getInstance()->__('Author Role', 'pop-userstance-processors');
        }

        return parent::getLabelText($component, $props);
    }

    public function getInputClass(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT:
                return GD_URE_FormInput_MultiAuthorRole::class;
        }

        return parent::getInputClass($component);
    }

    public function getName(array $component): string
    {
        switch ($component[1]) {
            case self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT:
                return 'role';
        }

        return parent::getName($component);
    }

    public function getFilterInputTypeResolver(array $component): InputTypeResolverInterface
    {
        return match($component[1]) {
            self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT => $this->stringScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $component): int
    {
        return match($component[1]) {
            self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $component): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($component[1]) {
            self::COMPONENT_FILTERINPUT_AUTHORROLE_MULTISELECT => $translationAPI->__('', ''),
            default => null,
        };
    }
}



