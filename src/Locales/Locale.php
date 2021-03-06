<?php

namespace zkelo\Unitpay\Locales;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use zkelo\Unitpay\Interfaces\LocaleInterface;

/**
 * Base abstract Locale class
 *
 * This class provides base methods and properties for future use in Locale classes
 *
 * @author Aleksandr Riabov <ar161ru@gmail.com>
 * @version 1.0.0
 */
abstract class Locale implements LocaleInterface
{
    /**
     * {@inheritDoc}
     */
    public static function rawMessages(): array
    {
        $raw = [];

        $arrayIterator = new RecursiveArrayIterator(static::messages());
        $iterator = new RecursiveIteratorIterator($arrayIterator);

        foreach ($iterator as $value) {
            $keys = [];
            $range = range(0, $iterator->getDepth());

            foreach ($range as $depth) {
                $keys[] = $iterator->getSubIterator($depth)->key();
            }

            $dotKey = implode('.', $keys);
            $raw[$dotKey] = $value;
        }

        return $raw;
    }
}
