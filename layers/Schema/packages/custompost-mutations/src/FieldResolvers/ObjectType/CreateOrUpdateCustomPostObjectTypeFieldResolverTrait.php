<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait CreateOrUpdateCustomPostObjectTypeFieldResolverTrait
{
    protected TranslationAPIInterface $translationAPI;
    protected HooksAPIInterface $hooksAPI;
    protected CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver;
    protected IDScalarTypeResolver $idScalarTypeResolver;
    protected StringScalarTypeResolver $stringScalarTypeResolver;

    #[Required]
    public function autowireCreateOrUpdateCustomPostObjectTypeFieldResolverTrait(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
        StringScalarTypeResolver $stringScalarTypeResolver,
    ): void {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
        $this->idScalarTypeResolver = $idScalarTypeResolver;
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }

    private function getCreateOrUpdateCustomPostSchemaFieldArgNameResolvers(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        bool $addCustomPostID,
        ?ConcreteTypeResolverInterface $concreteTypeResolver = null,
    ): array {
        $schemaFieldArgNameResolvers = array_merge(
            $addCustomPostID ? [
                MutationInputProperties::ID => $this->idScalarTypeResolver,
            ] : [],
            [
                MutationInputProperties::TITLE => $this->stringScalarTypeResolver,
                MutationInputProperties::CONTENT => $this->stringScalarTypeResolver,
                MutationInputProperties::STATUS => $this->customPostStatusEnumTypeResolver,
            ]
        );
        return $this->hooksAPI->applyFilters(
            SchemaDefinitionHelpers::HOOK_UPDATE_SCHEMA_FIELD_ARGS,
            $schemaFieldArgNameResolvers,
            $objectTypeResolver,
            $fieldName,
            $concreteTypeResolver
        );
    }
    
    private function getCreateOrUpdateCustomPostSchemaFieldArgDescription(
        string $fieldArgName,
    ): ?string {
        return match ($fieldArgName) {
            MutationInputProperties::ID => $this->translationAPI->__('The ID of the custom post to update', 'custompost-mutations'),
            MutationInputProperties::TITLE => $this->translationAPI->__('The title of the custom post', 'custompost-mutations'),
            MutationInputProperties::CONTENT => $this->translationAPI->__('The content of the custom post', 'custompost-mutations'),
            MutationInputProperties::STATUS => $this->translationAPI->__('The status of the custom post', 'custompost-mutations'),
            default => null,
        };
    }
    
    private function getCreateOrUpdateCustomPostSchemaFieldArgDefaultValue(
        string $fieldArgName,
    ): mixed {
        return null;
    }
    
    private function getCreateOrUpdateCustomPostSchemaFieldArgTypeModifiers(
        string $fieldArgName,
    ): ?int {
        return match ($fieldArgName) {
            MutationInputProperties::ID => SchemaTypeModifiers::MANDATORY,
            default => null,
        };
    }
}