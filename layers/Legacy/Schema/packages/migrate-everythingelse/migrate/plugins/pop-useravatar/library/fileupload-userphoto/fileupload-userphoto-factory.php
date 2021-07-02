<?php

class GD_FileUpload_UserPhotoFactory
{
    private static $instance;

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }
}
