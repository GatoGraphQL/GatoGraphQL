<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Overrides\TypeAPIs;

use PoPCMSSchema\PostTagsWP\TypeAPIs\PostTagTypeAPI as UpstreamPostTagTypeAPI;

use GraphQLAPI\WPFakerSchema\DataProvider\DataProviderInterface;

class PostTagTypeAPI extends UpstreamPostTagTypeAPI
{
    use TypeAPITrait;

    private ?DataProviderInterface $dataProvider = null;

    final public function setDataProvider(DataProviderInterface $dataProvider): void
    {
        $this->dataProvider = $dataProvider;
    }
    final protected function getDataProvider(): DataProviderInterface
    {
        return $this->dataProvider ??= $this->instanceManager->getInstance(DataProviderInterface::class);
    }
}
