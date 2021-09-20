<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;

class InstallSystemMutationResolver extends AbstractMutationResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
        );
    }

    public function executeMutation(array $form_data): mixed
    {
        // Save the new version on the DB
        update_option('PoP:version', ApplicationInfoFacade::getInstance()->getVersion());

        // Execute install everywhere
        $this->hooksAPI->doAction('PoP:system-install');
        return true;
    }
}
