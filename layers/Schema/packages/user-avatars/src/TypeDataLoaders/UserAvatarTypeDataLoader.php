<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\TypeDataLoaders;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoPSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;

class UserAvatarTypeDataLoader extends AbstractTypeDataLoader
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        protected UserAvatarRuntimeRegistryInterface $userAvatarRuntimeRegistry,
    ) {
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
        );
    }

    public function getObjects(array $ids): array
    {
        return array_map(
            fn (string | int $id) => $this->userAvatarRuntimeRegistry->getUserAvatar($id),
            $ids
        );
    }
}
