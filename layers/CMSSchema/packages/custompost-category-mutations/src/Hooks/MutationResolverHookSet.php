<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\Hooks;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\CustomPostMutations\Constants\GenericCustomPostCRUDHookNames;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI = null;

    final public function setQueryableCategoryTypeAPI(QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI): void
    {
        $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
    }
    final protected function getQueryableCategoryTypeAPI(): QueryableCategoryTypeAPIInterface
    {
        if ($this->queryableCategoryTypeAPI === null) {
            /** @var QueryableCategoryTypeAPIInterface */
            $queryableCategoryTypeAPI = $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
            $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
        }
        return $this->queryableCategoryTypeAPI;
    }

    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getQueryableCategoryTypeAPI();
    }

    protected function getCategoryTaxonomyName(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): string {
        /** @var string */
        return $fieldDataAccessor->getValue(MutationInputProperties::TAXONOMY);
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