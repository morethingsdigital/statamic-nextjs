<?php

namespace MoreThingsDigital\StatamicNextJs\Traits;

use Illuminate\Support\Facades\Config;

trait ValidationEnabled
{
    public function isEnabled(): bool
    {
        return Config::get('nextjs.enabled', false);
    }

    public function abortIfDisabled()
    {
        if (!$this->isEnabled()) {
            return;
        }
    }
}
