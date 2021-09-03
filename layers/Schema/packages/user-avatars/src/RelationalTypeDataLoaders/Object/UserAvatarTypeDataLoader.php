<?php

declare(strict_types=1);

namespace PoPSchema\UserAvatars\RelationalTypeDataLoaders\Object;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\Object\AbstractObjectTypeDataLoader;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoPSchema\UserAvatars\RuntimeRegistries\UserAvatarRuntimeRegistryInterface;

class UserAvatarTypeDataLoader extends AbstractObjectTypeDataLoader
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
