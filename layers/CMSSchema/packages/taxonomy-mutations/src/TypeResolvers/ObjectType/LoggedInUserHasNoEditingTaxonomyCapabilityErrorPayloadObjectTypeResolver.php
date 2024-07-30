<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\TaxonomyMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader = null;

    final public function setLoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader(LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader): void
    {
        $this->loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader;
    }
    final protected function getLoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit taxonomies"', 'customposts');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
