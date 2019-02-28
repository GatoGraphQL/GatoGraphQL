<?php
namespace PoP\CMSModel;

trait Dataloader_ParamTrait
{
    protected function getParamName()
    {
        return '';
    }

    public function getDbobjectIds($data_properties)
    {
    
        // When editing a post in the frontend, set param "pid"
        if ($id = $_REQUEST[$this->getParamName()]) {
            if (is_array($id)) {
                return \PoP\Engine\Utils::limitResults($id);
            }

            return array($id);
        }

        return array();
    }
}
