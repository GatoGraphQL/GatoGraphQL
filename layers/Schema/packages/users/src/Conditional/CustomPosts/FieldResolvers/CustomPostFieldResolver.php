<?php

declare(strict_types=1);

namespace PoPSchema\Users\Conditional\CustomPosts\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\Users\FieldInterfaceResolvers\WithAuthorFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\Users\Conditional\CustomPosts\Facades\CustomPostUserTypeAPIFacade;
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
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var WithAuthorFieldInterfaceResolver
         */
        $resolver = $instanceManager->getInstance(WithAuthorFieldInterfaceResolver::class);
        return $resolver;
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'author' => $translationAPI->__('The post\'s author', ''),
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
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
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
