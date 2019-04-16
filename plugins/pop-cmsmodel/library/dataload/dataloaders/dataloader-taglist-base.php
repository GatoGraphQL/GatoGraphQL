<?php
namespace PoP\CMSModel;

abstract class Dataloader_TagListBase extends Dataloader_TagBase
{
    use Dataloader_ListTrait;

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        return $query;
    }
    
    protected function getOrderbyDefault()
    {
        return \PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:dbcolumn:orderby:tags:count');
    }

    protected function getOrderDefault()
    {
        return 'DESC';
    }
    
    public function executeQuery($query, array $options = [])
    {
        $taxonomyapi = \PoP\Taxonomy\FunctionAPI_Factory::getInstance();
        return $taxonomyapi->getTags($query, $options);
    }
    
    public function executeQueryIds($query)
    {
    
        // $query['fields'] = 'ids';
        $options = [
            'return-type' => POP_RETURNTYPE_IDS,
        ];
        return $this->executeQuery($query, $options);
    }
}
