<?php

declare(strict_types=1);

namespace PoPSchema\Posts\RelationalTypeDataLoaders\ObjectType;

use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class PostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    protected PostTypeAPIInterface $postTypeAPI;
    public function __construct(
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        ModuleProcessorManagerInterface $moduleProcessorManager,
        CustomPostTypeAPIInterface $customPostTypeAPI,
        PostTypeAPIInterface $postTypeAPI,
    ) {
        $this->postTypeAPI = $postTypeAPI;
        parent::__construct(
            $hooksAPI,
            $instanceManager,
            $nameResolver,
            $moduleProcessorManager,
            $customPostTypeAPI,
        );
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->postTypeAPI->getPosts($query, $options);
    }
}
