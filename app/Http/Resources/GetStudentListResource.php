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
        $returnData['role']['uuid'] = $this->user->role->uuid;
        $returnData['role']['title'] = $this->user->role->title;
        $returnData['university_name'] = $this->user->student_detail->university_name;
        $returnData['college_name'] = $this->user->student_detail->college_name;
        $returnData['preferred_language'] = $this->user->student_detail->preferred_language;
        $returnData['other_information'] = $this->user->student_detail->other_information;
        $returnData['stream']['uuid'] = $this->stream->uuid;
        $returnData['stream']['title'] = $this->stream->title;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['title'] = $this->subject->title;
        $returnData['city']['uuid'] = $this->user->city_detail->id;
        $returnData['city']['title'] = $this->user->city_detail->name;
        $returnData['full_image_path'] = $this->user->full_image_path;
        $returnData['rating'] = 0;
        $returnData['total_reviews'] = 0;
        $returnData['total_notes'] = 0;
        $returnData['total_queries'] = 0;



        return $returnData;
    }
}
