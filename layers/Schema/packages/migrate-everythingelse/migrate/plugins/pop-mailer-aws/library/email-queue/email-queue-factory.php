<?php

class PoP_Mailer_EmailQueueFactory
{
    protected static $instance;

    public static function setInstance(PoP_Mailer_EmailQueue $instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): PoP_Mailer_EmailQueue
    {
        return self::$instance;
    }
}
