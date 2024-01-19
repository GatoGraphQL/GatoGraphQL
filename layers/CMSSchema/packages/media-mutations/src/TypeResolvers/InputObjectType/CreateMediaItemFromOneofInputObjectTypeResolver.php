<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class CreateMediaItemFromOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    private ?CreateMediaItemFromContentInputObjectTypeResolver $createMediaItemFromContentInputObjectTypeResolver = null;

    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        if ($this->urlScalarTypeResolver === null) {
            /** @var URLScalarTypeResolver */
            $urlScalarTypeResolver = $this->instanceManager->getInstance(URLScalarTypeResolver::class);
            $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        }
        return $this->urlScalarTypeResolver;
    }
    final public function setCreateMediaItemFromContentInputObjectTypeResolver(CreateMediaItemFromContentInputObjectTypeResolver $createMediaItemFromContentInputObjectTypeResolver): void
    {
        $this->createMediaItemFromContentInputObjectTypeResolver = $createMediaItemFromContentInputObjectTypeResolver;
    }
    final protected function getCreateMediaItemFromContentInputObjectTypeResolver(): CreateMediaItemFromContentInputObjectTypeResolver
    {
        if ($this->createMediaItemFromContentInputObjectTypeResolver === null) {
            /** @var CreateMediaItemFromContentInputObjectTypeResolver */
            $createMediaItemFromContentInputObjectTypeResolver = $this->instanceManager->getInstance(CreateMediaItemFromContentInputObjectTypeResolver::class);
            $this->createMediaItemFromContentInputObjectTypeResolver = $createMediaItemFromContentInputObjectTypeResolver;
        }
        return $this->createMediaItemFromContentInputObjectTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'CreateMediaItemFromInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::URL => $this->getURLScalarTypeResolver(),
            MutationInputProperties::CONTENTS => $this->getCreateMediaItemFromContentInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::URL => $this->__('Upload the attachment from a URL', 'media-mutations'),
            MutationInputProperties::CONTENTS => $this->__('Create the attachment by passing the file name and body', 'media-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
