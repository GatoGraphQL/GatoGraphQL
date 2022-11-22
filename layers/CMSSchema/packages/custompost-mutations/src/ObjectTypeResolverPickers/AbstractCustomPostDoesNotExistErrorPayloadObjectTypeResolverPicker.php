<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\CustomPostDoesNotExistErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotExistErrorPayload;

abstract class AbstractCustomPostDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements CustomPostDoesNotExistErrorPayloadObjectTypeResolverPickerInterface
{
    private ?CustomPostDoesNotExistErrorPayloadObjectTypeResolver $customPostDoesNotExistErrorPayloadObjectTypeResolver = null;

    final public function setCustomPostDoesNotExistErrorPayloadObjectTypeResolver(CustomPostDoesNotExistErrorPayloadObjectTypeResolver $customPostDoesNotExistErrorPayloadObjectTypeResolver): void
    {
        $this->customPostDoesNotExistErrorPayloadObjectTypeResolver = $customPostDoesNotExistErrorPayloadObjectTypeResolver;
    }
    final protected function getCustomPostDoesNotExistErrorPayloadObjectTypeResolver(): CustomPostDoesNotExistErrorPayloadObjectTypeResolver
    {
        /** @var CustomPostDoesNotExistErrorPayloadObjectTypeResolver */
        return $this->customPostDoesNotExistErrorPayloadObjectTypeResolver ??= $this->instanceManager->getInstance(CustomPostDoesNotExistErrorPayloadObjectTypeResolver::class);
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCustomPostDoesNotExistErrorPayloadObjectTypeResolver();
    }

    public function isInstanceOfType(object $object): bool
    {
        return $object instanceof CustomPostDoesNotExistErrorPayload;
    }

    public function isIDOfType(string|int $objectID): bool
    {
        // @todo Retrieve type from registry!!!!
        return false;
    }
}
