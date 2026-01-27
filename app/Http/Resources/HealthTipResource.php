<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthTipResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'source' => $this->source,
        ];
    }
}
