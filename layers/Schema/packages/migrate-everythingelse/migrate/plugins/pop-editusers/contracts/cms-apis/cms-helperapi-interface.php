<?php
namespace PoP\EditUsers;

interface HelperAPI
{
    public function sanitizeUsername(string $username);
}