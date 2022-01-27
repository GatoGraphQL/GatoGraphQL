<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\Root\App;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class SaveDefinitionFileMutationResolver extends AbstractMutationResolver
{
    public function executeMutation(array $form_data): mixed
    {
        App::doAction('PoP:system:save-definition-file');
        return true;
    }
}
