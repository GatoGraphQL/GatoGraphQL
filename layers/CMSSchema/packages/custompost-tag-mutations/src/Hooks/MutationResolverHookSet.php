<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\GenericCustomPostCRUDHookNames;
use PoPCMSSchema\CustomPostTagMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final public function setTaxonomyTermTypeAPI(TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI): void
    {
        $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
    }
    final protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        if ($this->taxonomyTermTypeAPI === null) {
            /** @var TaxonomyTermTypeAPIInterface */
            $taxonomyTermTypeAPI = $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
            $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
        }
        return $this->taxonomyTermTypeAPI;
    }

    protected function getValidateCreateOrUpdateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::VALIDATE_CREATE_OR_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return GenericCustomPostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
