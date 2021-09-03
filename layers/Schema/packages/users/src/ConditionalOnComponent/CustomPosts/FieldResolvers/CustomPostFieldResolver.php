<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
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

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->getSchemaFieldType($fieldName);
        }
        return parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'author' => $this->translationAPI->__('The post\'s author', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->getSchemaFieldTypeModifiers($fieldName);
        }
        return parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
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

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->getFieldTypeResolverClass($fieldName);
        }

        return parent::resolveFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
