<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request)
    {
         return [
            'id' => $this->id,
            'project' => new ProjectResource($this->project),
            'start_date' => $this->StartDate,
            'finish_date' => $this->FinishDate,
            'status' => $this->Status,
            'review_status' => $this->ReviewStatus,
            'submitted_code' => $this->SubmittedCode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
