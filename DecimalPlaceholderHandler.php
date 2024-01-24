<?php

namespace FpDbTest;

class DecimalPlaceholderHandler extends AbstractPlaceholderHandler
{
    use Nullable;

    function getSpecifier(): string
    {
        return 'd';
    }

    function handle($value)
    {
        return (int) $value;
    }
}
