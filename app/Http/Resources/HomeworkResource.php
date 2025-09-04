<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkResource extends JsonResource
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
            'title' => $this->title,
            'is_completed' => $this->is_completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'student' => $this->whenLoaded('student', function () {
                return new HomeworkResource($this->student);
            }),
            'teacher' => $this->whenLoaded('teacher', function () {
                return new UserResource($this->teacher);
            }),
            'exercises' => $this->whenLoaded('exercises', function () {
                return ExerciseResource::collection($this->exercises);
            }),
        ];
    }
}
