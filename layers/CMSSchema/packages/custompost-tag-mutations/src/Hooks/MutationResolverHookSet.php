<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\Hooks;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\CustomPostTagMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\GenericCustomPostTagTypeMutationAPIInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?GenericCustomPostTagTypeMutationAPIInterface $genericCustomPostTagTypeMutationAPI = null;
    private ?QueryableTagTypeAPIInterface $queryableTagTypeAPI = null;

    final public function setGenericCustomPostTagTypeMutationAPI(GenericCustomPostTagTypeMutationAPIInterface $genericCustomPostTagTypeMutationAPI): void
    {
        $this->genericCustomPostTagTypeMutationAPI = $genericCustomPostTagTypeMutationAPI;
    }
    final protected function getGenericCustomPostTagTypeMutationAPI(): GenericCustomPostTagTypeMutationAPIInterface
    {
        if ($this->genericCustomPostTagTypeMutationAPI === null) {
            /** @var GenericCustomPostTagTypeMutationAPIInterface */
            $genericCustomPostTagTypeMutationAPI = $this->instanceManager->getInstance(GenericCustomPostTagTypeMutationAPIInterface::class);
            $this->genericCustomPostTagTypeMutationAPI = $genericCustomPostTagTypeMutationAPI;
        }
        return $this->genericCustomPostTagTypeMutationAPI;
    }
    final public function setQueryableTagTypeAPI(QueryableTagTypeAPIInterface $queryableTagTypeAPI): void
    {
        $this->queryableTagTypeAPI = $queryableTagTypeAPI;
    }
    final protected function getQueryableTagTypeAPI(): QueryableTagTypeAPIInterface
    {
        if ($this->queryableTagTypeAPI === null) {
            /** @var QueryableTagTypeAPIInterface */
            $queryableTagTypeAPI = $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
            $this->queryableTagTypeAPI = $queryableTagTypeAPI;
        }
        return $this->queryableTagTypeAPI;
    }

    protected function getCustomPostType(): string
    {
        return '';
    }

    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }
}
