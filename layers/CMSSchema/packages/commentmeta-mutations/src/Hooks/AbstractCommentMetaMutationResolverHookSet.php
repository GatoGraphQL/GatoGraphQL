<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\Hooks;

use PoPCMSSchema\CommentMetaMutations\MutationResolvers\MutateCommentMetaMutationResolverTrait;
use PoPCMSSchema\CommentMetaMutations\TypeAPIs\CommentMetaTypeMutationAPIInterface;
use PoPCMSSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPCMSSchema\CommentMutations\Constants\CommentCRUDHookNames;
use PoPCMSSchema\MetaMutations\Hooks\AbstractMetaMutationResolverHookSet;
use PoPCMSSchema\MetaMutations\TypeAPIs\EntityMetaTypeMutationAPIInterface;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

abstract class AbstractCommentMetaMutationResolverHookSet extends AbstractMetaMutationResolverHookSet
{
    use MutateCommentMetaMutationResolverTrait;

    private ?CommentMetaTypeMutationAPIInterface $commentMetaTypeMutationAPI = null;
    private ?CommentMetaTypeAPIInterface $commentMetaTypeAPI = null;

    final protected function getCommentMetaTypeMutationAPI(): CommentMetaTypeMutationAPIInterface
    {
        if ($this->commentMetaTypeMutationAPI === null) {
            /** @var CommentMetaTypeMutationAPIInterface */
            $commentMetaTypeMutationAPI = $this->instanceManager->getInstance(CommentMetaTypeMutationAPIInterface::class);
            $this->commentMetaTypeMutationAPI = $commentMetaTypeMutationAPI;
        }
        return $this->commentMetaTypeMutationAPI;
    }
    final protected function getCommentMetaTypeAPI(): CommentMetaTypeAPIInterface
    {
        if ($this->commentMetaTypeAPI === null) {
            /** @var CommentMetaTypeAPIInterface */
            $commentMetaTypeAPI = $this->instanceManager->getInstance(CommentMetaTypeAPIInterface::class);
            $this->commentMetaTypeAPI = $commentMetaTypeAPI;
        }
        return $this->commentMetaTypeAPI;
    }

    protected function getEntityMetaTypeMutationAPI(): EntityMetaTypeMutationAPIInterface
    {
        return $this->getCommentMetaTypeMutationAPI();
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getCommentMetaTypeAPI();
    }
    protected function getErrorPayloadHookName(): string
    {
        return CommentCRUDHookNames::ERROR_PAYLOAD;
    }
}
