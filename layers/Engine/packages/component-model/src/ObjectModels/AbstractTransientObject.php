<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectModels;

use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

/**
 * A Transient Object is automatically added to the Object Dictionary
 * under the class of the object.
 */
abstract class AbstractTransientObject implements TransientObjectInterface
{
    use StandaloneServiceTrait;

    private ?ObjectDictionaryInterface $objectDictionary = null;

    final public function setObjectDictionary(ObjectDictionaryInterface $objectDictionary): void
    {
        $this->objectDictionary = $objectDictionary;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        /** @var ObjectDictionaryInterface */
        return $this->objectDictionary ??= InstanceManagerFacade::getInstance()->getInstance(ObjectDictionaryInterface::class);
    }

    /**
     * Static ID generator: all Transient Objects, from whatever class,
     * will have different IDs.
     */
    public static int $counter = 0;

    public readonly int $id;

    public function __construct()
    {
        self::$counter++;
        $this->id = self::$counter;

        // Register the object in the registry
        $this->getObjectDictionary()->set(get_called_class(), $this->getID(), $this);
    }

    public function getID(): int|string
    {
        return $this->id;
    }
}
