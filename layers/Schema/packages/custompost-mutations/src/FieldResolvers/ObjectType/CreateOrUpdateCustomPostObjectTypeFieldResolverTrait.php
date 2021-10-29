<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

trait CreateOrUpdateCustomPostObjectTypeFieldResolverTrait
{
    // use BasicServiceTrait;

    private ?CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    public function setCustomPostStatusEnumTypeResolver(CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver): void
    {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }
    protected function getCustomPostStatusEnumTypeResolver(): CustomPostStatusEnumTypeResolver
    {
        return $this->customPostStatusEnumTypeResolver ??= $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
    }
    public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }

    private function getCreateOrUpdateCustomPostSchemaFieldArgNameTypeResolvers(
        bool $addCustomPostID,
    ): array {
        return array_merge(
            $addCustomPostID ? [
                MutationInputProperties::ID => $this->getIdScalarTypeResolver(),
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
