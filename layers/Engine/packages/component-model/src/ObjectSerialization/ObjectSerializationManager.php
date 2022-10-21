<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectSerialization;

use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\BasicServiceTrait;
use stdClass;

class ObjectSerializationManager implements ObjectSerializationManagerInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string,ObjectSerializerInterface>
     */
    public array $objectSerializers = [];

    final public function addObjectSerializer(ObjectSerializerInterface $objectSerializer): void
    {
        $this->objectSerializers[$objectSerializer->getObjectClassToSerialize()] = $objectSerializer;
    }

    public function serialize(object $object): string|int|float|bool|array|stdClass
    {
        // Find the Serialize that serializes this object
        $objectSerializer = null;
        /** @var string|false */
        $classToSerialize = $object::class;
        while ($objectSerializer === null && $classToSerialize !== false) {
            $objectSerializer = $this->objectSerializers[$classToSerialize] ?? null;
            $classToSerialize = \get_parent_class($classToSerialize);
        }
        if ($objectSerializer !== null) {
            return $objectSerializer->serialize($object);
        }

        /**
         * No Serializer found. Then call the '__serialize' method of the object,
         * expecting it to implement it. If it doesn't, it will throw an exception,
         * so the developer will be made aware to create the corresponding Serializer
         * for that object class
         */
        if (!method_exists($object, '__serialize')) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('The object of class \'%s\' does not support method \'__serialize\'', 'component-model'),
                    get_class($object)
                )
            );
        }
        return $object->__serialize();
    }
}
