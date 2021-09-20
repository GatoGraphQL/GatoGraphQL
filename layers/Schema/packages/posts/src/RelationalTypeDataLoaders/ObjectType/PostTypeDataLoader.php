<?php

declare(strict_types=1);

namespace PoPSchema\Posts\RelationalTypeDataLoaders\ObjectType;

use PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPSchema\Posts\TypeAPIs\PostTypeAPIInterface;

class PostTypeDataLoader extends AbstractCustomPostTypeDataLoader
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\LooseContracts\NameResolverInterface $nameResolver,
        \PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface $moduleProcessorManager,
        \PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI,
        protected PostTypeAPIInterface $postTypeAPI,
    ) {
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
