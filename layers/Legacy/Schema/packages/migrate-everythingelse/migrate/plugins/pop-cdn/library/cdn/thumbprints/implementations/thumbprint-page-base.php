<?php
use PoP\Engine\Facades\Formatters\DateFormatterFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\Pages\Facades\PageTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

class PoP_CDN_Thumbprint_PageBase extends PoP_CDN_ThumbprintBase
{
    public function getQuery()
    {
        return array(
            'limit' => 1,
            'orderby' => NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:customposts:modified'),
            'order' => 'DESC',
            'page-status' => \PoPSchema\CustomPosts\Types\Status::PUBLISHED,
        );
    }

    public function executeQuery($query, array $options = []): array
    {
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        return $pageTypeAPI->getPages($query, $options);
    }

    public function getTimestamp($page_id)
    {
        // Doing it the manual way
        $pageTypeAPI = PageTypeAPIFacade::getInstance();
        $cmspagesresolver = \PoPSchema\Pages\ObjectPropertyResolverFactory::getInstance();
        $page = $pageTypeAPI->getPage($page_id);
        $dateFormatter = DateFormatterFacade::getInstance();
        $dateFormatter->format('U', $cmspagesresolver->getPageModified($page));
    }
}
