<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->Title,
            'short_description' => $this->ShortDescription,
            'long_description'  => $this->LongDescription,
            'time_limit'        => $this->TimeLimit,
            'difficulty'        => $this->Difficulty,
            'xp_reward'         => $this->XPReward,

            'technologies' => $this->technologies->map(function ($tech) {
                return [
                    'id'            => $tech->id,
                    'extra_xp'      => $tech->ExtraXP,

                    'framework' => $tech->framework ? [
                        'id'    => $tech->framework->id,
                        'title' => $tech->framework->title,
                        'icon'  => $tech->framework->framework_url,
                    ] : null,

                    'platform' => $tech->platform ? [
                        'id'    => $tech->platform->id,
                        'title' => $tech->platform->title,
                        'icon'  => $tech->platform->platform_url,
                    ] : null,

                    'programming_language' => $tech->programmingLanguage ? [
                        'id'    => $tech->programmingLanguage->id,
                        'title' => $tech->programmingLanguage->title,
                        'icon'  => $tech->programmingLanguage->programminglanguage_url,
                    ] : null,
                ];
            }),
        ];
    }
}
