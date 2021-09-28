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
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowirePostTypeDataLoader(
        PostTypeAPIInterface $postTypeAPI,
    ) {
        $this->postTypeAPI = $postTypeAPI;
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->postTypeAPI->getPosts($query, $options);
    }
}
