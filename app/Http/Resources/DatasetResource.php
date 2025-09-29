<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DatasetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'category'    => $this->category->name ?? null,
            'author'      => $this->author->name ?? null,
            'views'       => $this->views,
            'downloads'   => $this->downloads,
            'published_at'=> $this->published_at,
            'file_path'   => $this->file_path ? url('storage/' . $this->file_path) : null,
            'api_url'     => $this->api_url,
            'values'      => $this->whenLoaded('values', function () {
                return $this->values->map(function ($v) {
                    return [
                        'date'   => $v->date,
                        'region' => $v->region->name ?? null,
                        'value'  => $v->value,
                    ];
                });
            }),
        ];
    }
}
