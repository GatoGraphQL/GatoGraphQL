<?php
namespace PoP\CMS;

interface FrontendContentAPI extends ContentAPI
{
	public function autoembed($content);
}
