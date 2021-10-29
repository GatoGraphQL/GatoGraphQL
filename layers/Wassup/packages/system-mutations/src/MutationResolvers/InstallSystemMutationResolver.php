<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class InstallSystemMutationResolver extends AbstractMutationResolver
{
    public function executeMutation(array $form_data): mixed
    {
        // Save the new version on the DB
        update_option('PoP:version', ApplicationInfoFacade::getInstance()->getVersion());

        // Execute install everywhere
        $this->getHooksAPI()->doAction('PoP:system-install');
        return true;
    }
}
