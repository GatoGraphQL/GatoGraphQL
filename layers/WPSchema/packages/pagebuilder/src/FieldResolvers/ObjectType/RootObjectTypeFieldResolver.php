<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\FieldResolvers\ObjectType;

use PoPWPSchema\PageBuilder\Services\PageBuilderServiceInterface;
use PoPWPSchema\PageBuilder\TypeResolvers\EnumType\PageBuilderProvidersEnumTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?PageBuilderProvidersEnumTypeResolver $pageBuilderProvidersEnumTypeResolver = null;
    private ?PageBuilderServiceInterface $pageBuilderService = null;

    final protected function getPageBuilderProvidersEnumTypeResolver(): PageBuilderProvidersEnumTypeResolver
    {
        if ($this->pageBuilderProvidersEnumTypeResolver === null) {
            /** @var PageBuilderProvidersEnumTypeResolver */
            $pageBuilderProvidersEnumTypeResolver = $this->instanceManager->getInstance(PageBuilderProvidersEnumTypeResolver::class);
            $this->pageBuilderProvidersEnumTypeResolver = $pageBuilderProvidersEnumTypeResolver;
        }
        return $this->pageBuilderProvidersEnumTypeResolver;
    }
    final protected function getPageBuilderService(): PageBuilderServiceInterface
    {
        if ($this->pageBuilderService === null) {
            /** @var PageBuilderServiceInterface */
            $pageBuilderService = $this->instanceManager->getInstance(PageBuilderServiceInterface::class);
            $this->pageBuilderService = $pageBuilderService;
        }
        return $this->pageBuilderService;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'pageBuilders',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'pageBuilders' => $this->__('The Page Builders installed on the site', 'pagebuilder'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'pageBuilders' => $this->getPageBuilderProvidersEnumTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'pageBuilders' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'pageBuilders':
                return array_keys($this->getPageBuilderService()->getProviderNameServices());
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
