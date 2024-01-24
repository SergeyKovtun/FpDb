<?php

namespace FpDbTest;

class EmptyPlaceholderHandler extends AbstractPlaceholderHandler
{
    use Nullable;

    function getSpecifier(): string
    {
        return ' ';
    }

    function handle($value)
    {
        return $this->handlePrimitive($value) . ' ';
    }
}
