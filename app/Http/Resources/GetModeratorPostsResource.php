<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetModeratorPostsResource extends JsonResource
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
        $returnData['image_path'] = $this->full_image_path;
//        $returnData['subject']['uuid'] = $this->moderator_subject->subject->title;
        return $returnData;    }
}
