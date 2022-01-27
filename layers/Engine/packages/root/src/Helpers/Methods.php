<?php

declare(strict_types=1);

namespace PoP\Root\Helpers;

class Methods
{
    /**
     * This function is an implementation of a recursive `array_intersect_assoc`, so that in the RouteModuleProcessor we can ask for conditions recursively (eg: array('routing' => array('postType' => 'event')))
     * Modified from https://stackoverflow.com/questions/4627076/php-question-how-to-array-intersect-assoc-recursively
     */
    public static function arrayIntersectAssocRecursive(mixed &$arr1, mixed &$arr2): mixed
    {
        if (!is_array($arr1) || !is_array($arr2)) {
            return (string) $arr1 == (string) $arr2 ? $arr1 : null;
        }

        $commonkeys = array_intersect(
            array_keys($arr1),
            array_keys($arr2)
        );
        $ret = array();
        foreach ($commonkeys as $key) {
            $value = self::arrayIntersectAssocRecursive($arr1[$key], $arr2[$key]);
            if (!is_null($value)) {
                $ret[$key] = $value;
            }
        }

        // If no values, then must return null, so it's avoided when asking is_null() above
        return $ret ? $ret : null;
    }

    /**
     * `array_diff` doesn't work with multidimensional arrays,
     * this functions does.
     *
     * @see https://stackoverflow.com/a/29526501
     *
     * @param mixed[] $arr1
     * @param mixed[] $arr2
     * @return mixed[]
     */
    public static function arrayDiffRecursive(array $arr1, array $arr2): array
    {
        $outputDiff = [];

        foreach ($arr1 as $key => $value) {
            //if the key exists in the second array, recursively call this function
            //if it is an array, otherwise check if the value is in arr2
            if (array_key_exists($key, $arr2)) {
                if (is_array($value)) {
                    $recursiveDiff = self::arrayDiffRecursive($value, $arr2[$key]);

                    if (count($recursiveDiff)) {
                        $outputDiff[$key] = $recursiveDiff;
                    }
                } elseif (!in_array($value, $arr2)) {
                    $outputDiff[$key] = $value;
                }
            } elseif (!in_array($value, $arr2)) {
                //if the key is not in the second array, check if the value is in
                //the second array (this is a quirk of how array_diff works)
                $outputDiff[$key] = $value;
            }
        }

        return $outputDiff;
    }
}
