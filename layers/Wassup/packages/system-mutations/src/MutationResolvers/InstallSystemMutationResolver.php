<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Exception\AbstractException;
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

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        // Save the new version on the DB
        update_option('PoP:version', $this->getApplicationInfo()->getVersion());

        // Execute install everywhere
        App::doAction('PoP:system-install');
        return true;
    }
}
