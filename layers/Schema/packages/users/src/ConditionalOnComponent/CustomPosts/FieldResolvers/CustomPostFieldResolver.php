<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Users\FieldInterfaceResolvers\WithAuthorFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\Users\ConditionalOnComponent\CustomPosts\Facades\CustomPostUserTypeAPIFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;

class CustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            WithAuthorFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'author',
        ];
    }

    protected function getWithAuthorFieldInterfaceResolverInstance(): FieldInterfaceResolverInterface
    {
        /**
         * @var WithAuthorFieldInterfaceResolver
         */
        $resolver = $this->instanceManager->getInstance(WithAuthorFieldInterfaceResolver::class);
        return $resolver;
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->getSchemaFieldType($fieldName);
        }
        return parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'author' => $this->translationAPI->__('The post\'s author', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->isSchemaFieldResponseNonNullable($fieldName);
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPostUserTypeAPI = CustomPostUserTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'author':
                return $customPostUserTypeAPI->getAuthorID($resultItem);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->getFieldTypeResolverClass($fieldName);
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
