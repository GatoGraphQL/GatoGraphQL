<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\MutationResolvers;

use PoPCMSSchema\CategoryMutations\Exception\CategoryTermCRUDMutationException;
use PoPCMSSchema\CategoryMutations\TypeAPIs\CategoryTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\AbstractMutateTaxonomyTermMutationResolver;

abstract class AbstractMutateCategoryTermMutationResolver extends AbstractMutateTaxonomyTermMutationResolver implements CategoryTermMutationResolverInterface
{
    use MutateCategoryTermMutationResolverTrait;

    private ?CategoryTypeMutationAPIInterface $categoryTypeMutationAPI = null;

    final public function setCategoryTypeMutationAPI(CategoryTypeMutationAPIInterface $categoryTypeMutationAPI): void
    {
        $this->categoryTypeMutationAPI = $categoryTypeMutationAPI;
    }
    final protected function getCategoryTypeMutationAPI(): CategoryTypeMutationAPIInterface
    {
        if ($this->categoryTypeMutationAPI === null) {
            /** @var CategoryTypeMutationAPIInterface */
            $categoryTypeMutationAPI = $this->instanceManager->getInstance(CategoryTypeMutationAPIInterface::class);
            $this->categoryTypeMutationAPI = $categoryTypeMutationAPI;
        }
        return $this->categoryTypeMutationAPI;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated taxonomy
     * @throws CategoryTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getCategoryTypeMutationAPI()->updateCategoryTerm($taxonomyTermID, $taxonomyName, $taxonomyData);
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws CategoryTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeCreateTaxonomyTerm(string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getCategoryTypeMutationAPI()->createCategoryTerm($taxonomyName, $taxonomyData);
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws CategoryTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeDeleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->getCategoryTypeMutationAPI()->deleteCategoryTerm($taxonomyTermID, $taxonomyName);
    }

    protected function isHierarchical(): bool
    {
        return true;
    }
}
