<?php 

abstract class PoP_Mailer_EmailQueueBase implements PoP_Mailer_EmailQueue
{
    public function __construct()
    {
        PoP_Mailer_EmailQueueFactory::setInstance($this);
    }
}
