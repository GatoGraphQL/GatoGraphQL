<?php
namespace PoP\Engine\Impl;

/**
 * Ajax Load Posts Library
 */

define('GD_DATALOADER_NIL', 'nil');

class Dataloader_Nil extends \PoP\Engine\QueryDataDataloader
{
    public function getName()
    {
        return GD_DATALOADER_NIL;
    }
}

/**
 * Initialize
 */
new Dataloader_Nil();
