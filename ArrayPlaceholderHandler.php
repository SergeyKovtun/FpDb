<?php

namespace FpDbTest;

use Exception;

class ArrayPlaceholderHandler extends AbstractPlaceholderHandler
{

    function getSpecifier(): string
    {
        return 'a';
    }

    function handle($value)
    {
        if (!is_array($value)) {
            throw new Exception('Array only support for #a');
        }
        if (array_is_list($value)) {
            return implode(', ', $value);
        } else {
            $arrayParts = [];
            foreach ($value as $key => $v) {
                $arrayParts[] = $this->escapeId($key) . ' = ' . $this->handlePrimitive($v);
            }
            return implode(', ', $arrayParts);
        }
    }
}
