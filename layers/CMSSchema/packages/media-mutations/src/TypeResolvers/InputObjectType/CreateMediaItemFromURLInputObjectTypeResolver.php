<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class CreateMediaItemFromURLInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'CreateMediaItemFromURLInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Create and upload the attachment from a URL', 'media-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::FILENAME => $this->getStringScalarTypeResolver(),
            MutationInputProperties::SOURCE => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getInputFieldTypeModifiers(string $inputFieldName): int
    {
        return match ($inputFieldName) {
            MutationInputProperties::SOURCE
                => SchemaTypeModifiers::MANDATORY,
            default
                => parent::getInputFieldTypeModifiers($inputFieldName),
        };
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::FILENAME => $this->__('File name (to override the one from the URL source)', 'media-mutations'),
            MutationInputProperties::SOURCE => $this->__('URL source', 'media-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
