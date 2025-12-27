<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * @template TKey of array-key
 * @template TValue
 *
 * @implements Arrayable<TKey, TValue>
 */
abstract readonly class BaseDTO implements Arrayable, JsonSerializable
{
    /**
     * Convert the DTO to JSON.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}
