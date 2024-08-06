<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagMutations\MutationResolvers;

use PoPCMSSchema\TagMutations\Exception\TagTermCRUDMutationException;
use PoPCMSSchema\TagMutations\TypeAPIs\TagTypeMutationAPIInterface;
use PoPCMSSchema\TaxonomyMutations\MutationResolvers\AbstractMutateTaxonomyTermMutationResolver;

abstract class AbstractMutateTagTermMutationResolver extends AbstractMutateTaxonomyTermMutationResolver implements TagTermMutationResolverInterface
{
    use MutateTagTermMutationResolverTrait;

    private ?TagTypeMutationAPIInterface $tagTypeMutationAPI = null;

    final public function setTagTypeMutationAPI(TagTypeMutationAPIInterface $tagTypeMutationAPI): void
    {
        $this->tagTypeMutationAPI = $tagTypeMutationAPI;
    }
    final protected function getTagTypeMutationAPI(): TagTypeMutationAPIInterface
    {
        if ($this->tagTypeMutationAPI === null) {
            /** @var TagTypeMutationAPIInterface */
            $tagTypeMutationAPI = $this->instanceManager->getInstance(TagTypeMutationAPIInterface::class);
            $this->tagTypeMutationAPI = $tagTypeMutationAPI;
        }
        return $this->tagTypeMutationAPI;
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the updated taxonomy
     * @throws TagTermCRUDMutationException If there was an error (eg: taxonomy term does not exist)
     */
    protected function executeUpdateTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTagTypeMutationAPI()->updateTagTerm($taxonomyTermID, $taxonomyName, $taxonomyData);
    }

    /**
     * @param array<string,mixed> $taxonomyData
     * @return string|int the ID of the created taxonomy
     * @throws TagTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeCreateTaxonomyTerm(string $taxonomyName, array $taxonomyData): string|int
    {
        return $this->getTagTypeMutationAPI()->createTagTerm($taxonomyName, $taxonomyData);
    }

    /**
     * @return bool `true` if the operation successful, `false` if the term does not exist
     * @throws TagTermCRUDMutationException If there was an error (eg: some taxonomy term creation validation failed)
     */
    protected function executeDeleteTaxonomyTerm(string|int $taxonomyTermID, string $taxonomyName): bool
    {
        return $this->getTagTypeMutationAPI()->deleteTagTerm($taxonomyTermID, $taxonomyName);
    }
}
