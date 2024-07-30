<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CategoryMutations\ObjectModels\LoggedInUserHasNoEditingCategoryCapabilityErrorPayload;
use PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType\LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver(LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoEditingCategoryCapabilityErrorPayload::class;
    }
}
