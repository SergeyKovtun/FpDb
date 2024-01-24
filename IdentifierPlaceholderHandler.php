<?php

namespace FpDbTest;

use Exception;

class IdentifierPlaceholderHandler extends AbstractPlaceholderHandler
{

    function getSpecifier(): string
    {
        return '#';
    }

    function handle($value): string
    {
        if (is_array($value)) {
            return implode(', ', array_map(fn($item) => $this->escapeId($item), $value));
        } elseif (is_string($value)) {
            return $this->escapeId($value);
        } else {
            throw new Exception('Only array or string support for #');
        }
    }
}
