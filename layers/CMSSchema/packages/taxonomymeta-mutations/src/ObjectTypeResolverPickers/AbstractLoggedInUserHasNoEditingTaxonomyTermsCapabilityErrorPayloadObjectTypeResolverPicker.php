<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMetaMutations\ObjectModels\LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayload;
use PoPCMSSchema\TaxonomyMetaMutations\TypeResolvers\ObjectType\LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingTaxonomyTermsCapabilityErrorPayloadObjectTypeResolver = null;

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
