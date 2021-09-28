<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

abstract class AbstractFieldResolver implements FieldResolverInterface
{
    protected TranslationAPIInterface $translationAPI;
    protected HooksAPIInterface $hooksAPI;
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function autowireAbstractFieldResolver(TranslationAPIInterface $translationAPI, HooksAPIInterface $hooksAPI, InstanceManagerInterface $instanceManager)
    {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
        $this->instanceManager = $instanceManager;
    }

    public function getAdminFieldNames(): array
    {
        return [];
    }

    /**
     * Apply hook to override the values, eg: by the Field Deprecation List
     */
    final protected function triggerHookToOverrideSchemaDefinition(
        array $schemaDefinition,
        TypeResolverInterface $typeResolver,
        string $fieldName,
        array $fieldArgs,
    ): array {
        $hookName = HookHelpers::getSchemaDefinitionForFieldHookName(
            get_class($typeResolver),
            $fieldName
        );
        return $this->hooksAPI->applyFilters(
            $hookName,
            $schemaDefinition,
            $typeResolver,
            $this,
            $fieldName,
            $fieldArgs
        );
    }
}
