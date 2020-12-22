<?php

class PoP_EmailSender_SentEmailsManager
{
    protected static $sentemail_users;

    public static function init(): void
    {
        self::$sentemail_users = array();
    }

    public static function getSentemailUsers($category)
    {
        return self::$sentemail_users[$category] ?? array();
    }

    public static function addSentemailUsers($category, $users)
    {
        self::$sentemail_users[$category] = self::$sentemail_users[$category] ?? array();
        self::$sentemail_users[$category] = array_unique(
            array_merge(
                self::$sentemail_users[$category],
                $users
            )
        );
    }
}

/**
 * Initialization
 */
PoP_EmailSender_SentEmailsManager::init();
