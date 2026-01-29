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
            'content' => $this->content,
            'category' => $this->category,
            'source' => $this->source,
            'author' => $this->author,
            'published_at' => $this->published_at?->toISOString(),
            'read_more_url' => $this->read_more_url,
        ];
    }
}
