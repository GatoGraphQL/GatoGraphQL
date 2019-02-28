<?php
namespace PoP\Engine;

abstract class FilterComponent_Metaquery_UserBase extends FilterComponent_MetaqueryBase
{
    public function getMetaqueryKey()
    {
        return MetaManager::getMetaKey($this->getMetaKey(), GD_META_TYPE_USER);
    }

    public function getMetaKey()
    {
        return null;
    }
}
