<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray( $request)
    {
      /*   return [
            'id' => $this->id,
            'platform' => $this->platform ? [
                'id' => $this->platform->id,
                'title' => $this->platform->Title,
                'icon' => $this->platform->Icon
            ] : null,
            'framework' => $this->framework ? [
                'id' => $this->framework->id,
                'title' => $this->framework->title,
                'icon' => $this->framework->Icon
            ] : null,
            'programming_language' => $this->programmingLanguage ? [
                'id' => $this->programmingLanguage->id,
                'title' => $this->programmingLanguage->Title,
                'icon' => $this->programmingLanguage->Icon
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]; */
           return [
        'id' => $this->id,
        'user_project_id' => $this->userproject_id,
        'platform' => $this->when(!is_null($this->Platform_id), [
            'id' => $this->Platform_id,
            'name' => $this->platform->Title ?? null
        ]),
        'framework' => $this->when(!is_null($this->framework_id), [
            'id' => $this->framework_id,
            'name' => $this->framework->title ?? null
        ]),
        'programming_language' => $this->when(!is_null($this->programminglanguage_id), [
            'id' => $this->programminglanguage_id,
            'name' => $this->programmingLanguage->Title ?? null
        ])
    ];
    }
}
