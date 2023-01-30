<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\WPDataModel;

interface WPDataModelProviderInterface
{
    /**
     * @return string[]
     */
    public function getFilteredNonGraphQLAPIPluginCustomPostTypes(): array;
}
