<?php

class PoP_AddCoauthors_API_Base
{
    public function __construct()
    {
        PoP_AddCoauthors_APIFactory::setInstance($this);
    }
}
