<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMetaMutations\Hooks;

use PoPCMSSchema\CustomPostMetaMutations\MutationResolvers\MutateCustomPostMetaMutationResolverTrait;
use PoPCMSSchema\CustomPostMetaMutations\TypeAPIs\CustomPostMetaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPCMSSchema\CustomPostMutations\Constants\CustomPostCRUDHookNames;
use PoPCMSSchema\MetaMutations\Hooks\AbstractMetaMutationResolverHookSet;
use PoPCMSSchema\MetaMutations\TypeAPIs\EntityMetaTypeMutationAPIInterface;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;

abstract class AbstractCustomPostMetaMutationResolverHookSet extends AbstractMetaMutationResolverHookSet
{
    use MutateCustomPostMetaMutationResolverTrait;

    private ?CustomPostMetaTypeMutationAPIInterface $customPostMetaTypeMutationAPI = null;
    private ?CustomPostMetaTypeAPIInterface $customPostMetaTypeAPI = null;

    final protected function getCustomPostMetaTypeMutationAPI(): CustomPostMetaTypeMutationAPIInterface
    {
        if ($this->customPostMetaTypeMutationAPI === null) {
            /** @var CustomPostMetaTypeMutationAPIInterface */
            $customPostMetaTypeMutationAPI = $this->instanceManager->getInstance(CustomPostMetaTypeMutationAPIInterface::class);
            $this->customPostMetaTypeMutationAPI = $customPostMetaTypeMutationAPI;
        }
        return $this->customPostMetaTypeMutationAPI;
    }
    final protected function getCustomPostMetaTypeAPI(): CustomPostMetaTypeAPIInterface
    {
        if ($this->customPostMetaTypeAPI === null) {
            /** @var CustomPostMetaTypeAPIInterface */
            $customPostMetaTypeAPI = $this->instanceManager->getInstance(CustomPostMetaTypeAPIInterface::class);
            $this->customPostMetaTypeAPI = $customPostMetaTypeAPI;
        }
        return $this->customPostMetaTypeAPI;
    }

    protected function getEntityMetaTypeMutationAPI(): EntityMetaTypeMutationAPIInterface
    {
        return $this->getCustomPostMetaTypeMutationAPI();
    }

    protected function getMetaTypeAPI(): MetaTypeAPIInterface
    {
        return $this->getCustomPostMetaTypeAPI();
    }
    protected function getErrorPayloadHookName(): string
    {
        return CustomPostCRUDHookNames::ERROR_PAYLOAD;
    }
}
