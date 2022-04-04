<?php

declare(strict_types=1);

namespace PoPSitesWassup\CustomPostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\MutationInputProperties as UpstreamMutationInputProperties;

class MutationInputProperties extends UpstreamMutationInputProperties
{
    public final const REFERENCES = 'references';
    public final const TOPICS = 'topics';
    public final const VOLUNTEERSNEEDED = 'volunteersneeded';
    public final const APPLIESTO = 'appliesto';

    // Watch out! This functionality is duplicated!
    // Adding categories is handled by custompost-category-mutations
    // via some hook. Check!
    public final const CATEGORIES = 'categories';
}
