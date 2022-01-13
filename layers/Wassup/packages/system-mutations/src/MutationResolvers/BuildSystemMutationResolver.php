<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class BuildSystemMutationResolver extends AbstractMutationResolver
{
    public function executeMutation(array $form_data): mixed
    {
        \PoP\Root\App::getHookManager()->doAction('PoP:system-build');
        return true;
    }
}
