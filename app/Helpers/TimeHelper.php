<?php

declare(strict_types=1);

namespace App\Helpers;

class TimeHelper
{
    public function process(int $seconds): string
    {
        return gmdate('H:i:s', $seconds);
    }
}
