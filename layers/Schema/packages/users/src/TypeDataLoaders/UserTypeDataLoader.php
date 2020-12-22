<?php

declare(strict_types=1);

namespace PoPSchema\Users\TypeDataLoaders;

use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\ComponentModel\TypeDataLoaders\AbstractTypeQueryableDataLoader;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class UserTypeDataLoader extends AbstractTypeQueryableDataLoader
{
    public function getFilterDataloadingModule(): ?array
    {
        return [\PoP_Users_Module_Processor_FieldDataloads::class, \PoP_Users_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_USERLIST];
    }

    public function getObjects(array $ids): array
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $ret = array();
        foreach ($ids as $user_id) {
            $ret[] = $cmsusersapi->getUserById($user_id);
        }
        return $ret;
    }

    public function getDataFromIdsQuery(array $ids): array
    {
        $query = array(
            'include' => $ids
        );
        return $query;
    }

    protected function getOrderbyDefault()
    {
        return NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:users:name');
    }

    protected function getOrderDefault()
    {
        return 'ASC';
    }

    protected function getQueryHookName()
    {
        // Get the role either from a provided attr, and allow PoP User Platform to set the default role
        return 'UserTypeDataLoader:query';
    }

    public function executeQuery($query, array $options = [])
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        return $cmsusersapi->getUsers($query, $options);
    }

    public function executeQueryIds($query): array
    {
        // $query['fields'] = 'ID';
        $options = [
            'return-type' => ReturnTypes::IDS,
        ];
        return (array)$this->executeQuery($query, $options);
    }
}
