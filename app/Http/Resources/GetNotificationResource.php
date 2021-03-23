<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetNotificationResource extends JsonResource
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
        $returnData['id'] = $this->id;
        $returnData['uuid'] = $this->uuid;
        $returnData['date'] = getDateInFormat($this->created_at);
        $returnData['title'] = $this->title;
        $returnData['message'] = $this->message;
        $returnData['type'] = $this->type;
        return $returnData;
    }
}
