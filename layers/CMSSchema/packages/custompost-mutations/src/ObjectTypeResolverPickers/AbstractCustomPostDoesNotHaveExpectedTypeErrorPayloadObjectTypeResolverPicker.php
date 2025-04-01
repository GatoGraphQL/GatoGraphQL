<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotHaveExpectedTypeErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractCustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver $customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver = null;

    final protected function getCustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver(): CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver
    {
        if ($this->customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver === null) {
            /** @var CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver */
            $customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver::class);
            $this->customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver = $customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver;
        }
        return $this->customPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCustomPostDoesNotHaveExpectedTypeErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CustomPostDoesNotHaveExpectedTypeErrorPayload::class;
    }
}
