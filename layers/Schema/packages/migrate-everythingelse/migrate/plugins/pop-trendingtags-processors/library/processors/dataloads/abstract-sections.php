<?php
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;

abstract class Abstract_PoP_TrendingTags_Module_Processor_SectionDataloads extends PoP_Module_Processor_SectionDataloadsBase
{
    public function getDBObjectIDOrIDs(array $module, array &$props, &$data_properties): string | int | array
    {
        $query_args = $data_properties[DataloadingConstants::QUERYARGS];
        // One Week by default for the trending topics
        $days = $query_args['days'] ?? POP_TRENDINGTAGS_DAYS_TRENDINGTAGS;
        $cmstrendingtagsapi = \PoP\TrendingTags\FunctionAPIFactory::getInstance();
        return $cmstrendingtagsapi->getTrendingHashtagIds($days, $query_args['limit'], $query_args['offset']);
    }

    public function getQueryInputOutputHandlerClass(array $module): ?string
    {
        return \PoP\TrendingTags\QueryInputOutputHandler_TrendingTagList::class;
    }

    public function getTypeResolverClass(array $module): ?string
    {
        return PostTagTypeResolver::class;
    }
}



