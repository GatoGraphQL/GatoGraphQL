<?php
namespace PoP\Engine;

class Dataloader_Manager
{
    public $dataloaders;
    
    public function __construct()
    {
        Dataloader_Manager_Factory::setInstance($this);
        return $this->dataloaders = array();
    }
    
    public function add($dataloader)
    {
        $this->dataloaders[$dataloader->getName()] = $dataloader;
    }
    
    public function get($name)
    {
        $dataloader = $this->dataloaders[$name];
        if (!$dataloader) {
            throw new \Exception(sprintf('There is no Dataloader with name \'%s\' (%s)', $name, fullUrl()));
        }

        return $dataloader;
    }
}
    
/**
 * Initialize
 */
new Dataloader_Manager();
