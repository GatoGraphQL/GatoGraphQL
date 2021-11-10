<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;

trait CreateOrUpdateCustomPostObjectTypeFieldResolverTrait
{
    abstract protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver;
    abstract protected function getIDScalarTypeResolver(): IDScalarTypeResolver;
    abstract protected function getStringScalarTypeResolver(): StringScalarTypeResolver;

    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    private function getCreateOrUpdateCustomPostSchemaFieldArgNameTypeResolvers(
        bool $addCustomPostID,
    ): array {
        return array_merge(
            $addCustomPostID ? [
                MutationInputProperties::ID => $this->getIDScalarTypeResolver(),
            ] : [],
            [
                MutationInputProperties::TITLE => $this->getStringScalarTypeResolver(),
                MutationInputProperties::CONTENT => $this->getStringScalarTypeResolver(),
                MutationInputProperties::STATUS => $this->getCustomPostStatusEnumTypeResolver(),
            ]
        );
    }

    private function getCreateOrUpdateCustomPostSchemaFieldArgDescription(
        string $fieldArgName,
    ): ?string {
        return match ($fieldArgName) {
            MutationInputProperties::ID => $this->getTranslationAPI()->__('The ID of the custom post to update', 'custompost-mutations'),
            MutationInputProperties::TITLE => $this->getTranslationAPI()->__('The title of the custom post', 'custompost-mutations'),
            MutationInputProperties::CONTENT => $this->getTranslationAPI()->__('The content of the custom post', 'custompost-mutations'),
            MutationInputProperties::STATUS => $this->getTranslationAPI()->__('The status of the custom post', 'custompost-mutations'),
            default => null,
        };
    }

    private function getCreateOrUpdateCustomPostSchemaFieldArgTypeModifiers(
        string $fieldArgName,
    ): int {
        return match ($fieldArgName) {
            MutationInputProperties::ID => SchemaTypeModifiers::MANDATORY,
            default => SchemaTypeModifiers::NONE,
        };
    }
}
