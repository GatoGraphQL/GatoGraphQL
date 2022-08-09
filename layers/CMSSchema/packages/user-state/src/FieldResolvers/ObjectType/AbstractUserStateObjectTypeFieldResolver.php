<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserState\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;

abstract class AbstractUserStateObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
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

    /**
     * @param array<string, mixed> $fieldArgs
     * @return CheckpointInterface[]
     */
    public function getValidationCheckpoints(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): array {
        $validationCheckpoints = parent::getValidationCheckpoints(
            $objectTypeResolver,
            $fieldDataAccessor,
            $object,
        );
        $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
        return $validationCheckpoints;
    }
}
