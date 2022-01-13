<?php
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsFilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Symfony\Contracts\Service\Attribute\Required;

class PoP_Module_Processor_UserSelectableTypeaheadFilterInputs extends PoP_Module_Processor_UserSelectableTypeaheadFormComponentsBase implements DataloadQueryArgsFilterInputModuleProcessorInterface
{
    use DataloadQueryArgsSchemaFilterInputModuleProcessorTrait;

    public const MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES = 'filtercomponent-selectabletypeahead-profiles';

    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getModulesToProcess(): array
    {
        return array(
            [self::class, self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        );
    }

    public function getFilterInput(array $module): ?array
    {
        $filterInputs = [
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => [PoP_Module_Processor_FormsFilterInputProcessor::class, PoP_Module_Processor_FormsFilterInputProcessor::FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES],
        ];
        return $filterInputs[$module[1]] ?? null;
    }

    // public function isFiltercomponent(array $module)
    // {
    //     switch ($module[1]) {
    //         case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
    //             return true;
    //     }

    //     return parent::isFiltercomponent($module);
    // }

    public function getLabelText(array $module, array &$props)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return TranslationAPIFacade::getInstance()->__('Authors', 'pop-coreprocessors');
        }

        return parent::getLabelText($module, $props);
    }

    public function getInputSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return [PoP_Module_Processor_TypeaheadTextFormInputs::class, PoP_Module_Processor_TypeaheadTextFormInputs::MODULE_FORMINPUT_TEXT_TYPEAHEADPROFILES];
        }

        return parent::getInputSubmodule($module);
    }

    public function getComponentSubmodules(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                // Allow PoP Common User Roles to change this
                return \PoP\Root\App::applyFilters(
                    'UserSelectableTypeaheadFormInputs:components:profiles',
                    array(
                        [PoP_Module_Processor_UserTypeaheadComponentFormInputs::class, PoP_Module_Processor_UserTypeaheadComponentFormInputs::MODULE_TYPEAHEAD_COMPONENT_USERS],
                    )
                );
        }

        return parent::getComponentSubmodules($module);
    }

    public function getTriggerLayoutSubmodule(array $module)
    {
        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                return [PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::class, PoP_Module_Processor_UserSelectableTypeaheadTriggerFormComponents::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEADTRIGGER_PROFILES];
        }

        return parent::getTriggerLayoutSubmodule($module);
    }

    public function getName(array $module): string
    {
        switch ($module[1]) {
            case self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
                // Calling it either 'authors' or 'users' for some reason doesn't work!
                return 'profiles';
        }

        return parent::getName($module);
    }

    public function getFilterInputTypeResolver(array $module): InputTypeResolverInterface
    {
        return match($module[1]) {
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => $this->idScalarTypeResolver,
            default => $this->getDefaultSchemaFilterInputTypeResolver(),
        };
    }

    public function getFilterInputTypeModifiers(array $module): int
    {
        return match($module[1]) {
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => SchemaTypeModifiers::IS_ARRAY,
            default => SchemaTypeModifiers::NONE,
        };
    }

    public function getFilterInputDescription(array $module): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match ($module[1]) {
            self::MODULE_FILTERCOMPONENT_SELECTABLETYPEAHEAD_PROFILES => $translationAPI->__('', ''),
            default => null,
        };
    }
}



