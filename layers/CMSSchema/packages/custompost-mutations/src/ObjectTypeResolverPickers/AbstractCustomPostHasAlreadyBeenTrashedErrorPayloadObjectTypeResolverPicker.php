<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostHasAlreadyBeenTrashedErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver $customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = null;

    final protected function getCustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver(): CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver
    {
        if ($this->customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver === null) {
            /** @var CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver */
            $customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver::class);
            $this->customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver = $customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
        }
        return $this->customPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCustomPostHasAlreadyBeenTrashedErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CustomPostHasAlreadyBeenTrashedErrorPayload::class;
    }
}
