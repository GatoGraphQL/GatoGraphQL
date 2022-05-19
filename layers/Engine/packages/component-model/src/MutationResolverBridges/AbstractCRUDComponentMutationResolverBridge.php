<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolverBridges;

use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;

abstract class AbstractCRUDComponentMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected function skipDataloadIfError(): bool
    {
        return true;
    }

    protected function modifyDataProperties(array &$data_properties, string | int $result_id): void
    {
        parent::modifyDataProperties($data_properties, $result_id);

        // Modify the block-data-settings, saying to select the id of the newly created post
        $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($result_id);
    }
}
