<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\FieldResolvers\ObjectType;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Translation\TranslationAPIInterface;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;

trait UserStateObjectTypeFieldResolverTrait
{
    private ?UserLoggedInCheckpoint $userLoggedInCheckpoint = null;

    final public function setUserLoggedInCheckpoint(UserLoggedInCheckpoint $userLoggedInCheckpoint): void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    final protected function getUserLoggedInCheckpoint(): UserLoggedInCheckpoint
    {
        return $this->userLoggedInCheckpoint ??= $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
    }

    abstract protected function getTranslationAPI(): TranslationAPIInterface;

    /**
     * @param array<string, mixed> $fieldArgs
     * @return CheckpointInterface[]
     */
    protected function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $object,
            $fieldName,
            $fieldArgs,
        );
        $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
        return $validationCheckpoints;
    }
}
