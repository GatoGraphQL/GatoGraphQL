<?php

class PoP_ServerSide_LibrariesFactory
{
    protected static $jslibrary;
    protected static $jsruntime;
    protected static $popmanager;
    protected static $datastore;

    public static function setJslibraryInstance($jslibrary)
    {
        self::$jslibrary = $jslibrary;
    }

    public static function getJslibraryInstance()
    {
        return self::$jslibrary;
    }

    public static function setJsruntimeInstance($jsruntime)
    {
        self::$jsruntime = $jsruntime;
    }

    public static function getJsruntimeInstance()
    {
        return self::$jsruntime;
    }

    public static function setPopmanagerInstance($popmanager)
    {
        self::$popmanager = $popmanager;
    }

    public static function getPopmanagerInstance()
    {
        return self::$popmanager;
    }

    public static function setDatastoreInstance($datastore)
    {
        self::$datastore = $datastore;
    }

    public static function getDatastoreInstance()
    {
        return self::$datastore;
    }
}
