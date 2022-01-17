<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\MutationInputProperties as UpstreamMutationInputProperties;

class MutationInputProperties extends UpstreamMutationInputProperties
{
    public const REFERENCES = 'references';
    public const TOPICS = 'topics';
    public const VOLUNTEERSNEEDED = 'volunteersneeded';
    public const APPLIESTO = 'appliesto';

    // Watch out! This functionality is duplicated!
    // Adding categories is handled by custompost-category-mutations
    // via some hook. Check!
    public const CATEGORIES = 'categories';
}
