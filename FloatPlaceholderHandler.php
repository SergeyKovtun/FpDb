<?php

namespace FpDbTest;

class FloatPlaceholderHandler extends AbstractPlaceholderHandler
{
    use Nullable;

    function getSpecifier(): string
    {
        return 'f';
    }

    function handle($value): float
    {
        return (float) $value;
    }
}
