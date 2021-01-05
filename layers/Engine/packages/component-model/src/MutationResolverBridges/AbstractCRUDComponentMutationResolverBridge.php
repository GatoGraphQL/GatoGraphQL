<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolverBridges;

use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;

abstract class AbstractCRUDComponentMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected function skipDataloadIfError(): bool
    {
        return true;
    }

    /**
     * @param mixed $result_id Maybe an int, maybe a string
     */
    protected function modifyDataProperties(array &$data_properties, $result_id): void
    {
        parent::modifyDataProperties($data_properties, $result_id);

        // Modify the block-data-settings, saying to select the id of the newly created post
        $data_properties[DataloadingConstants::QUERYARGS]['include'] = array($result_id);
    }
}
