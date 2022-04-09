<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Overrides\TypeAPIs;

use GraphQLAPI\WPFakerSchema\App;
use GraphQLAPI\WPFakerSchema\Component;
use GraphQLAPI\WPFakerSchema\ComponentConfiguration;
use GraphQLAPI\WPFakerSchema\DataProvider\DataProviderInterface;
use WP_Term;

trait TaxonomyTypeAPITrait
{
    abstract protected function getDataProvider(): DataProviderInterface;

    /**
     * @param int[] $termIDs
     * @return WP_Term[]
     */
    protected function getFakeTerms(array $termIDs): array
    {
        return array_map(
            fn (array $fakeTermDataEntry) => App::getWPFaker()->term($fakeTermDataEntry),
            $this->getFakeTermDataEntries($termIDs)
        );
    }

    /**
     * @return array<array<string,mixed>>
     */
    protected function getAllFakeTermDataEntries(): array
    {
        return $this->getDataProvider()->getFixedDataset()['terms'] ?? [];
    }

    /**
     * @param int[] $termIDs
     * @return array<array<string,mixed>>
     */
    protected function getFakeTermDataEntries(?array $termIDs = null): array
    {
        if ($termIDs === []) {
            return [];
        }
        $termDataEntries = $this->getAllFakeTermDataEntries();
        if ($termIDs !== null) {
            $termDataEntries = array_filter(
                $termDataEntries,
                fn (array $termDataEntry) => in_array($termDataEntry['term_id'], $termIDs)
            );
        }

        /**
         * Convert "term_id" to "id", keep all other properties the same
         */
        return array_map(
            fn (array $termDataEntry) => [
                ...$termDataEntry,
                ...[
                    'id' => $termDataEntry['term_id'],
                ]
            ],
            $termDataEntries
        );
    }

    protected function getTerm(string | int $termObjectID, string $taxonomy = ''): ?WP_Term
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        $useFixedDataset = $componentConfiguration->useFixedDataset();

        if ($useFixedDataset) {
            $termDataEntries = $this->getFakeTermDataEntries();
            if ($termDataEntries === []) {
                return null;
            }
            $termID = $termDataEntries[0]['id'];
            $terms = $this->getFakeTerms([$termID]);
            return $terms[0];
        }

        return App::getWPFaker()->term([
            // Create a random new term with the requested ID
            'id' => $termObjectID
        ]);
    }
}
