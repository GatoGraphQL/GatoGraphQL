<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType\LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver = null;

    final protected function getLoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoAssigningTermsToTaxonomyCapabilityErrorPayload::class;
    }
}
