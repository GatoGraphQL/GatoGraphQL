<?php
namespace PoPSchema\Users;

interface ObjectPropertyResolver
{
    public function getUserLogin($user);
    public function getUserNicename($user);
    public function getUserSlug($user);
    public function getUserDisplayName($user);
    public function getUserFirstname($user);
    public function getUserLastname($user);
    public function getUserEmail($user);
    public function getUserId($user);
    public function getUserDescription($user);
    public function getUserWebsiteUrl($user);
}
