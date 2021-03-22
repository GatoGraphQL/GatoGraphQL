<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;

class InstallSystemMutationResolver extends AbstractMutationResolver
{
    public function execute(array $form_data): mixed
    {
        // Save the new version on the DB
        update_option('PoP:version', ApplicationInfoFacade::getInstance()->getVersion());

        // Execute install everywhere
        HooksAPIFacade::getInstance()->doAction('PoP:system-install');
        return true;
    }
}
