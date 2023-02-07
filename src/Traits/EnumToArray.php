<?php

declare(strict_types = 1);

namespace Omnipay\Cashu\Traits;

use function strtoupper;

trait EnumToArray
{
    /**
     * @param string $name
     *
     * @return static|null
     */
    public static function getByName(string $name) : static|null
    {
        foreach (self::cases() as $enum) {
            if (strtoupper($enum->name) === strtoupper($name)) {
                return $enum;
            }
        }

        return null;
    }
}
