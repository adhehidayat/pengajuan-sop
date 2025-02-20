<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class GenerateNumberExtensionRuntime implements RuntimeExtensionInterface
{
    public function doSomething($value): string
    {
        return str_pad((integer) $value,2 , '0', STR_PAD_LEFT);
    }
}
