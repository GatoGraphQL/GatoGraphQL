<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\MutationResolvers;

class UpdateGenericTaxonomyTermMutationResolver extends AbstractCreateUpdateGenericTaxonomyTermMutationResolver
{
    use UpdateTaxonomyTermMutationResolverTrait;
}
