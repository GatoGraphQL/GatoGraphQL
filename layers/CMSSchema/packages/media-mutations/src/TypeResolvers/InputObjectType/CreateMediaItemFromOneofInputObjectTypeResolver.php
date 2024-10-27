<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

class CreateMediaItemFromOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?CreateMediaItemFromContentInputObjectTypeResolver $createMediaItemFromContentInputObjectTypeResolver = null;
    private ?CreateMediaItemFromURLInputObjectTypeResolver $createMediaItemFromURLInputObjectTypeResolver = null;
    private ?MediaItemByOneofInputObjectTypeResolver $mediaItemByOneofInputObjectTypeResolver = null;

    final protected function getCreateMediaItemFromContentInputObjectTypeResolver(): CreateMediaItemFromContentInputObjectTypeResolver
    {
        if ($this->createMediaItemFromContentInputObjectTypeResolver === null) {
            /** @var CreateMediaItemFromContentInputObjectTypeResolver */
            $createMediaItemFromContentInputObjectTypeResolver = $this->instanceManager->getInstance(CreateMediaItemFromContentInputObjectTypeResolver::class);
            $this->createMediaItemFromContentInputObjectTypeResolver = $createMediaItemFromContentInputObjectTypeResolver;
        }
        return $this->createMediaItemFromContentInputObjectTypeResolver;
    }
    final protected function getCreateMediaItemFromURLInputObjectTypeResolver(): CreateMediaItemFromURLInputObjectTypeResolver
    {
        if ($this->createMediaItemFromURLInputObjectTypeResolver === null) {
            /** @var CreateMediaItemFromURLInputObjectTypeResolver */
            $createMediaItemFromURLInputObjectTypeResolver = $this->instanceManager->getInstance(CreateMediaItemFromURLInputObjectTypeResolver::class);
            $this->createMediaItemFromURLInputObjectTypeResolver = $createMediaItemFromURLInputObjectTypeResolver;
        }
        return $this->createMediaItemFromURLInputObjectTypeResolver;
    }
    final protected function getMediaItemByOneofInputObjectTypeResolver(): MediaItemByOneofInputObjectTypeResolver
    {
        if ($this->mediaItemByOneofInputObjectTypeResolver === null) {
            /** @var MediaItemByOneofInputObjectTypeResolver */
            $mediaItemByOneofInputObjectTypeResolver = $this->instanceManager->getInstance(MediaItemByOneofInputObjectTypeResolver::class);
            $this->mediaItemByOneofInputObjectTypeResolver = $mediaItemByOneofInputObjectTypeResolver;
        }
        return $this->mediaItemByOneofInputObjectTypeResolver;
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
            MutationInputProperties::MEDIAITEM_BY => $this->getMediaItemByOneofInputObjectTypeResolver(),
            MutationInputProperties::URL => $this->getCreateMediaItemFromURLInputObjectTypeResolver(),
            MutationInputProperties::CONTENTS => $this->getCreateMediaItemFromContentInputObjectTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::MEDIAITEM_BY => $this->__('Use the attachment from an existing media item', 'media-mutations'),
            MutationInputProperties::URL => $this->__('Upload the attachment from a URL', 'media-mutations'),
            MutationInputProperties::CONTENTS => $this->__('Create the attachment by passing the file name and body', 'media-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
