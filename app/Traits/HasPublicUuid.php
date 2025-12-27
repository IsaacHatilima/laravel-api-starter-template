<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasPublicUuid
{
    public function getRouteKeyName(): string
    {
        return 'public_id';
    }

    protected static function bootHasPublicUuid(): void
    {
        static::creating(function (self $model): void {
            $model->public_id ??= (string) Str::uuid();
        });
    }
}
