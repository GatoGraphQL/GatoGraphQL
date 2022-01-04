<?php

declare(strict_types=1);

namespace PoP\Root\Component;

/**
 * Initialize component
 */
abstract class AbstractComponentConfiguration implements ComponentConfigurationInterface
{
    /**
     * Cannot override the constructor
     */
    final function __construct()
    {        
    }
}
