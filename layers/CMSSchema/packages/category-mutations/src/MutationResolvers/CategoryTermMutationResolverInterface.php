<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

interface CategoryTermMutationResolverInterface extends MutationResolverInterface
{
    public function getTaxonomyName(): string;
}
