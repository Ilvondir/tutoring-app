<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assignment' => $this->assignment,
            'answer' => $this->answer,
            'order' => $this->order,
            'complete_date' => $this->complete_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'homework' => $this->whenLoaded('homework', function () {
                return new HomeworkResource($this->homework);
            }),
        ];
    }
}
