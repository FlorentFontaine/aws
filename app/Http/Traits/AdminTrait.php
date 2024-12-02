<?php

namespace App\Http\Traits;
trait AdminTrait
{
    public function isAdmin(): bool
    {
        return strpos($_SERVER['HTTP_HOST'], "localhost:") !== false;
    }
}
