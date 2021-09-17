<?php

declare(strict_types=1);

namespace App\Models;

class PageScripts
{
    private $scripts = [];

    public function addScript(string $script)
    {
        $this->scripts[] = $script;
    }

    public function print()
    {
        foreach ($this->scripts as $script) {
            echo $script;
        }
    }
}
