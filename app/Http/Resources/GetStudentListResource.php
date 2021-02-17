<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetStudentListResource extends JsonResource
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
        $returnData['uuid'] = $this->user->uuid;
        $returnData['name'] = $this->user->name;
        $returnData['university_name'] = $this->user->student_detail->university_name;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['title'] = $this->subject->title;
        $returnData['city']['uuid'] = $this->user->city_detail->id;
        $returnData['city']['title'] = $this->user->city_detail->name;
        $returnData['full_image_path'] = $this->user->full_image_path;

        return $returnData;
    }
}
