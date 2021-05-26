<?php
namespace PoPSchema\Users;

interface ObjectPropertyResolver
{
    public function getUserNicename($user);
    public function getUserSlug($user);
    public function getUserId($user);
}
