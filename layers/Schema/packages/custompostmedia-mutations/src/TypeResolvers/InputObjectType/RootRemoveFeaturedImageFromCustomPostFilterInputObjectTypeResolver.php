<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;

class RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'RootRemoveFeaturedImageFromCustomPostFilterInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Input to remove the featured image from a custom post', 'custompostmedia-mutations');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::CUSTOMPOST_ID => $this->getIDScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::CUSTOMPOST_ID => $this->__('The ID of the custom post', 'custompostmedia-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::CUSTOMPOST_ID => SchemaTypeModifiers::MANDATORY,
            default => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }
}
