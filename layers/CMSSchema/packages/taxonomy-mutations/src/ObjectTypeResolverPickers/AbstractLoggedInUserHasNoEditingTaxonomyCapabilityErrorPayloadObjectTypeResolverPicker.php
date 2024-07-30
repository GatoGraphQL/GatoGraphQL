<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\TaxonomyMutations\ObjectModels\LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayload;
use PoPCMSSchema\TaxonomyMutations\TypeResolvers\ObjectType\LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

abstract class AbstractLoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver = null;

    final public function setLoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver(LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver): void
    {
        $this->loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver;
    }
    final protected function getLoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver(): LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver
    {
        if ($this->loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver === null) {
            /** @var LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver */
            $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver::class);
            $this->loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver = $loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver;
        }
        return $this->loggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getLoggedInUserHasNoEditingTaxonomyCapabilityErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return LoggedInUserHasNoEditingTaxonomyCapabilityErrorPayload::class;
    }
}
