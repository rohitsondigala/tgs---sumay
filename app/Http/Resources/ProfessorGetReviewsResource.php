<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessorGetReviewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $returnData = array();
        $returnData['uuid'] = $this->uuid;
        $returnData['date'] = Carbon::parse($this->created_at)->format('d M Y');
        $returnData['rating'] = $this->rating;
        $returnData['description'] = $this->description;
        $returnData['user']['uuid'] = $this->from_user->uuid;
        $returnData['user']['name'] = $this->from_user->name;
        $returnData['user']['full_image_path'] = $this->from_user->full_image_path;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['title'] = $this->subject->title;

        return $returnData;
    }
}
