<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\Root\App;
use PoP\ComponentModel\Info\ApplicationInfoInterface;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class InstallSystemMutationResolver extends AbstractMutationResolver
{
    private ?ApplicationInfoInterface $applicationInfo = null;

    final public function setApplicationInfo(ApplicationInfoInterface $applicationInfo): void
    {
        $this->applicationInfo = $applicationInfo;
    }
    final protected function getApplicationInfo(): ApplicationInfoInterface
    {
        return $this->applicationInfo ??= $this->instanceManager->getInstance(ApplicationInfoInterface::class);
    }

    public function executeMutation(array $form_data): mixed
    {
        // Save the new version on the DB
        update_option('PoP:version', $this->getApplicationInfo()->getVersion());

        // Execute install everywhere
        App::getHookManager()->doAction('PoP:system-install');
        return true;
    }
}
