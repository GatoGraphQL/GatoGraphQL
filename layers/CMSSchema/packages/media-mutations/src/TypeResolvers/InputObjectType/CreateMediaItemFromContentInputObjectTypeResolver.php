<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\MediaMutations\TypeResolvers\ScalarType\AllowedMimeTypeEnumStringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

class CreateMediaItemFromContentInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?AllowedMimeTypeEnumStringScalarTypeResolver $allowedMimeTypeEnumStringScalarTypeResolver = null;

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
    final public function setAllowedMimeTypeEnumStringScalarTypeResolver(AllowedMimeTypeEnumStringScalarTypeResolver $allowedMimeTypeEnumStringScalarTypeResolver): void
    {
        $this->allowedMimeTypeEnumStringScalarTypeResolver = $allowedMimeTypeEnumStringScalarTypeResolver;
    }
    final protected function getAllowedMimeTypeEnumStringScalarTypeResolver(): AllowedMimeTypeEnumStringScalarTypeResolver
    {
        if ($this->allowedMimeTypeEnumStringScalarTypeResolver === null) {
            /** @var AllowedMimeTypeEnumStringScalarTypeResolver */
            $allowedMimeTypeEnumStringScalarTypeResolver = $this->instanceManager->getInstance(AllowedMimeTypeEnumStringScalarTypeResolver::class);
            $this->allowedMimeTypeEnumStringScalarTypeResolver = $allowedMimeTypeEnumStringScalarTypeResolver;
        }
        return $this->allowedMimeTypeEnumStringScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'CreateMediaItemFromContentInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Provide the data to create and upload the attachment', 'media-mutations');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::FILENAME => $this->getStringScalarTypeResolver(),
            MutationInputProperties::BODY => $this->getStringScalarTypeResolver(),
            MutationInputProperties::MIME_TYPE => $this->getAllowedMimeTypeEnumStringScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::FILENAME => $this->__('File name', 'media-mutations'),
            MutationInputProperties::BODY => $this->__('File body', 'media-mutations'),
            MutationInputProperties::MIME_TYPE => $this->__('File mime type', 'media-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
