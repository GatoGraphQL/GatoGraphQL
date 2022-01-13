<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class BuildSystemMutationResolver extends AbstractMutationResolver
{
    public function executeMutation(array $form_data): mixed
    {
        App::getHookManager()->doAction('PoP:system-build');
        return true;
    }
}
