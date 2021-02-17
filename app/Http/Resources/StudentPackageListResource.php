<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentPackageListResource extends JsonResource
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
        $returnData['package_uuid'] = $this->package->uuid;
        $returnData['title'] = $this->package->title;
        $returnData['slug'] = $this->package->slug;
        $returnData['full_image_path'] = $this->package->full_image_path;
        $returnData['description'] = $this->package->description;
        $returnData['purchase_date'] = $this->purchase_date;
        $returnData['expiry_date'] = $this->expiry_date;
        $returnData['duration_in_days'] = $this->duration_in_days;
        $returnData['price'] = $this->price;
        $returnData['is_purchased'] = $this->is_purchased;
        $returnData['stream']['id'] = $this->stream->uuid;
        $returnData['stream']['title'] = $this->stream->title;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['name'] = $this->subject->name;
        return $returnData;
    }
}
