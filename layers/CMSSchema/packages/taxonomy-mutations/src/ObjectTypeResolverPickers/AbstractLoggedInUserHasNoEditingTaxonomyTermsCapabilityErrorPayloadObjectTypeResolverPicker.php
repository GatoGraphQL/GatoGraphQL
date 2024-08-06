<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayload;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType\LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver(LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayload::class;
    }
}
