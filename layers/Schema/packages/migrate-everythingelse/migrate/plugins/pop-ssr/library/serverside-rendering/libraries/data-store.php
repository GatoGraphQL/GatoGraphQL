<?php
class PoP_ServerSide_DataStore
{

    //-------------------------------------------------
    // INTERNAL variables
    //-------------------------------------------------
    // Comment Leo 21/07/2017: since adding multicomponents for different domains, we group memory, database and userDatabase under property `store`, under which we specify the domain
    public $store;
    
    public function __construct()
    {
        PoP_ServerSide_LibrariesFactory::setDatastoreInstance($this);
        
        // Initialize internal variables
        $this->store = array();
    }
}

/**
 * Initialization
 */
new PoP_ServerSide_DataStore();
