<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Info\ApplicationInfoInterface;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;

class InstallSystemMutationResolver extends AbstractMutationResolver
{
    private ?ApplicationInfoInterface $applicationInfo = null;

    final public function setApplicationInfo(ApplicationInfoInterface $applicationInfo): void
    {
        $this->applicationInfo = $applicationInfo;
    }
    final protected function getApplicationInfo(): ApplicationInfoInterface
    {
        if ($this->applicationInfo === null) {
            /** @var ApplicationInfoInterface */
            $applicationInfo = $this->instanceManager->getInstance(ApplicationInfoInterface::class);
            $this->applicationInfo = $applicationInfo;
        }
        return $this->applicationInfo;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        // Save the new version on the DB
        update_option('PoP:version', $this->getApplicationInfo()->getVersion());

        // Execute install everywhere
        App::doAction('PoP:system-install');
        return true;
    }
}
