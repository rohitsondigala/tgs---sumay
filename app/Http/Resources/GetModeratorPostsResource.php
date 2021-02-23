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
        $returnData['title'] = $this->title;
        $returnData['description'] = $this->description;
        $returnData['subject']['uuid'] = !empty($this->moderator_subject) ? $this->moderator_subject->subject->uuid : null;
        $returnData['subject']['title'] = !empty($this->moderator_subject) ? $this->moderator_subject->subject->title : null;
        return $returnData;    }
}
