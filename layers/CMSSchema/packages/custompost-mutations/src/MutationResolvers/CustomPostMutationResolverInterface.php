<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;

interface CustomPostMutationResolverInterface extends MutationResolverInterface
{
    public function getCustomPostType(): string;
}
