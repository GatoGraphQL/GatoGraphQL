<?php
use PoP\Engine\Facades\Formatters\DateFormatterFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\Types\Status;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class PoP_CDN_Thumbprint_PostBase extends PoP_CDN_ThumbprintBase
{
    public function getQuery()
    {
        return array(
            'limit' => 1,
            'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:customposts:modified'),
            'order' => 'DESC',
            'status' => Status::PUBLISHED,
        );
    }

    public function executeQuery($query, array $options = [])
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        return $customPostTypeAPI->getCustomPosts($query, $options);
    }

    public function getTimestamp($post_id)
    {
        // Doing it the manual way
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $dateFormatter = DateFormatterFacade::getInstance();
        $dateFormatter->format('U', $customPostTypeAPI->getModifiedDate($post_id));
    }
}
