<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayload;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType\LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoDeletingTaxonomyTermCapabilityErrorPayload::class;
    }
}
