<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to delete a taxonomy term"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
