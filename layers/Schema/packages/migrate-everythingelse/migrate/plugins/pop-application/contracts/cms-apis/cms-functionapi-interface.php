<?php
namespace PoP\Application;

interface FunctionAPI
{
    public function isAdminPanel();
    public function getDocumentTitle();
    public function getSiteName();
    public function getSiteDescription();
}
