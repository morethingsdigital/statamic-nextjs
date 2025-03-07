<?php

namespace MoreThingsDigital\StatamicNextJs\Traits;

use Illuminate\Support\Facades\Config;

trait ValidationEnabled
{
    public function isEnabled(): bool
    {
        return Config::get('statamic.nextjs.enabled', false);
    }
}
