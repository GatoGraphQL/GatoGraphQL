<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Hooks\Facades\HooksAPIFacade;

class GenerateThemeMutationResolver extends AbstractMutationResolver
{
    public function execute(array $form_data): mixed
    {
        $this->hooksAPI->doAction('PoP:system-generate:theme');
        return true;
    }
}
