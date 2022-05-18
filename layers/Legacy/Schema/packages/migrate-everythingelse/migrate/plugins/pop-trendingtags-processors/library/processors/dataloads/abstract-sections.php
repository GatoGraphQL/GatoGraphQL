<?php
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\QueryInputOutputHandlerInterface;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;

abstract class Abstract_PoP_TrendingTags_Module_Processor_SectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public function getObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        $query_args = $data_properties[DataloadingConstants::QUERYARGS];
        // One Week by default for the trending topics
        $days = $query_args['days'] ?? POP_TRENDINGTAGS_DAYS_TRENDINGTAGS;
        $cmstrendingtagsapi = \PoP\TrendingTags\FunctionAPIFactory::getInstance();
        return $cmstrendingtagsapi->getTrendingHashtagIds($days, $query_args['limit'], $query_args['offset']);
    }

    public function getQueryInputOutputHandler(array $module): ?QueryInputOutputHandlerInterface
    {
        return $this->instanceManager->getInstance(\PoP\TrendingTags\QueryInputOutputHandler_TrendingTagList::class);
    }

    public function getRelationalTypeResolver(array $module): ?\PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
    {
        return $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
}



