<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class GenerateSystemMutationResolver extends AbstractMutationResolver
{
    public function executeMutation(array $form_data): mixed
    {
        $this->hooksAPI->doAction('PoP:system-generate');
        return true;
    }
}
