<?php

namespace App\Observers;

use App\Models\Gateway;
use Illuminate\Support\Str;

class GatewayObserver
{

    public function creating(Gateway $gateway): void
    {
        if (empty($gateway->slug)) {
            $gateway->slug = Str::slug($gateway->name);
        }
    }

    public function updating(Gateway $gateway): void
    {
        if ($gateway->isDirty('name')) {
            $gateway->slug = Str::slug($gateway->name);
        }
    }
}
