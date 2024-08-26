<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\GenericCustomPostCRUDHookNames;
use PoPCMSSchema\CustomPostTagMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostTagMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?QueryableTagTypeAPIInterface $queryableTagTypeAPI = null;

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

    /**
     * Retrieve the taxonomy passed via the `taxonomy` input.
     * If that's not possible (eg: on `createCustomPost.setTags`),
     * then retrieve it from queried object's CPT.
     */
    protected function getTagTaxonomyName(
        int|string $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): ?string {
        /** @var string|null */
        $taxonomName = $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
        if ($taxonomName === null) {
            return parent::getTagTaxonomyName(
                $customPostID,
                $fieldDataAccessor,
            );
        }
        return $taxonomName;
    }

    protected function getTagTypeAPI(): TagTypeAPIInterface
    {
        return $this->getQueryableTagTypeAPI();
    }

    protected function getValidateCreateOrUpdateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::VALIDATE_CREATE_OR_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
    protected function getErrorPayloadHookName(): string
    {
        return GenericCustomPostCRUDHookNames::ERROR_PAYLOAD;
    }
}
