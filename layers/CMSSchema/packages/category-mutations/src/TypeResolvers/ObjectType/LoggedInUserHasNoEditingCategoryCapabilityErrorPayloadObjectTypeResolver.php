<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\CategoryMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader = null;

    final public function setLoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader(LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader): void
    {
        $this->loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader;
    }
    final protected function getLoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoEditingCategoryCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit categorys"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoEditingCategoryCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
