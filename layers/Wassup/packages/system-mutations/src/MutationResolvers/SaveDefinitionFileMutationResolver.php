<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\Translation\TranslationAPIInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class SaveDefinitionFileMutationResolver extends AbstractMutationResolver
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
        $this->hooksAPI->doAction('PoP:system:save-definition-file');
        return true;
    }
}
