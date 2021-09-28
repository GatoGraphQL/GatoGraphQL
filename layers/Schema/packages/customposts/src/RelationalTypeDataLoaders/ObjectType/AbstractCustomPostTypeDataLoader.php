<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPIUtils;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

abstract class AbstractCustomPostTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;
    
    #[Required]
    public function autowireAbstractCustomPostTypeDataLoader(
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ) {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    public function getQueryToRetrieveObjectsForIDs(array $ids): array
    {
        return [
            'include' => $ids,
            'status' => CustomPostTypeAPIUtils::getPostStatuses(),
        ];
    }

    public function executeQuery($query, array $options = []): array
    {
        return $this->customPostTypeAPI->getCustomPosts($query, $options);
    }

    protected function getOrderbyDefault()
    {
        return $this->nameResolver->getName('popcms:dbcolumn:orderby:customposts:date');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }

    public function executeQueryIDs($query): array
    {
        $options = [
            QueryOptions::RETURN_TYPE => ReturnTypes::IDS,
        ];
        return $this->executeQuery($query, $options);
    }

    protected function getLimitParam($query_args)
    {
        return $this->hooksAPI->applyFilters(
            'CustomPostTypeDataLoader:query:limit',
            parent::getLimitParam($query_args)
        );
    }

    protected function getQueryHookName()
    {
        // Allow to add the timestamp for loadingLatest
        return 'CustomPostTypeDataLoader:query';
    }
}
