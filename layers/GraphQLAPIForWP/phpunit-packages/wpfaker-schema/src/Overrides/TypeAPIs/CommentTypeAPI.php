<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Overrides\TypeAPIs;

use GraphQLAPI\WPFakerSchema\DataProvider\DataProviderInterface;
use PoPCMSSchema\CommentsWP\TypeAPIs\CommentTypeAPI as UpstreamCommentTypeAPI;

class CommentTypeAPI extends UpstreamCommentTypeAPI
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
