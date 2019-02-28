<?php
namespace PoP\Engine;

abstract class FilterComponent_Metaquery_PostBase extends FilterComponent_MetaqueryBase
{
    public function getMetaqueryKey()
    {
        return MetaManager::getMetaKey($this->getMetaKey(), GD_META_TYPE_POST);
    }

    public function getMetaKey()
    {
        return null;
    }
}
