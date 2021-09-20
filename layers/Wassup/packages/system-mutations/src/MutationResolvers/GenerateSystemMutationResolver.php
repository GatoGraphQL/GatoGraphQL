<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class GenerateSystemMutationResolver extends AbstractMutationResolver
{
    public function __construct(
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
        );
    }
    
    public function executeMutation(array $form_data): mixed
    {
        $this->hooksAPI->doAction('PoP:system-generate');
        return true;
    }
}
