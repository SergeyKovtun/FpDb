<?php

namespace FpDbTest;

use Exception;

abstract class AbstractPlaceholderHandler
{
    private const SUPPORTS_TYPES = [
            'float',
            'int',
            'string',
            'bool',
            'NULL',
        ];

    abstract function getSpecifier(): string;

    abstract function handle($value);

    function doHandle($value)
    {
        if ($value === Database::SKIP) {
            return $value;
        }
        if (in_array(Nullable::class, class_uses($this)) && $value === null) {
            return 'NULL';
        }

        return $this->handle($value);
    }

    public function handlePrimitive($value)
    {
        $type = gettype($value);

        if (!in_array($type, self::SUPPORTS_TYPES)) {
            throw new Exception("Unsupported type $type for value $value");
        }

        if ($type == 'string') {
            return "'" . $value . "'";
        }

        if ($type == 'boolean') {
            return (int) $value;
        }

        if ($value === null) {
            return 'NULL';
        }
    }

    public function escapeId(string $string): string
    {
        return "`$string`";
    }
}
