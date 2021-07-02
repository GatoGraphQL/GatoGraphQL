<?php
namespace PoP\Application;

interface HelperAPI
{
    public function escapeHTML(string $text);
    public function escapeAttributes(string $text);
    public function makeClickable(string $text);
    public function convertLinebreaksToHTML(string $text): string;
    public function sanitizeTitle(string $title);
}