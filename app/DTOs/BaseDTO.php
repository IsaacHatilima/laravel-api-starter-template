<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements Arrayable<TKey, TValue>
 */
abstract readonly class BaseDTO implements Arrayable
{
    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}
