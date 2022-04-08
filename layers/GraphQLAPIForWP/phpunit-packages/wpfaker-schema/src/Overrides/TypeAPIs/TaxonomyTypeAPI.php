<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\Overrides\TypeAPIs;

use GraphQLAPI\WPFakerSchema\DataProvider\DataProviderInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\TaxonomyTypeAPI as UpstreamTaxonomyTypeAPI;

class TaxonomyTypeAPI extends UpstreamTaxonomyTypeAPI
{
    use TypeAPITrait;
    use TaxonomyTypeAPITrait;

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
