<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \Carbon\Carbon
 */
class DateTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'datetime' => $this->toISOString(),
            'human_diff' => $this->diffForHumans(),
            'human' => $this->toDayDateTimeString(),
        ];
    }
}
