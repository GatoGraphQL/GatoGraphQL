<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\ObjectType;

use PoPCMSSchema\TaxonomyMetaMutations\RelationalTypeDataLoaders\ObjectType\LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;

class LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver extends AbstractErrorPayloadObjectTypeResolver
{
    private ?LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader = null;

    final protected function getLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader(): LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader
    {
        if ($this->loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader === null) {
            /** @var LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader */
            $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader = $this->instanceManager->getInstance(LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader::class);
            $this->loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader = $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader;
        }
        return $this->loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader;
    }

    public function getTypeName(): string
    {
        return 'LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayload';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Error payload for: "The logged-in user has no permission to edit taxonomy terms"', 'taxonomymeta-mutations');
    }

    public function getRelationalTypeDataLoader(): RelationalTypeDataLoaderInterface
    {
        return $this->getLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeDataLoader();
    }
}
