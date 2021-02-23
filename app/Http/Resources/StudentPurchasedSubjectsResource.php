<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentPurchasedSubjectsResource extends JsonResource
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
        $returnData['uuid'] = $this->subject->uuid;
        $returnData['title'] = $this->subject->title;
        $returnData['is_purchased'] = $this->is_purchased;
        return $returnData;
    }
}
