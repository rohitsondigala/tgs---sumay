<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GetProfessorListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $userDetail = getUserDetail($request->user_uuid);
        $existingSubjects = getStudentSubjectsPurchased($userDetail);

        $returnData = array();
        $returnData['uuid'] = $this->user->uuid;
        $returnData['name'] = $this->user->name;
        $returnData['university_name'] = $this->user->professor_detail->university_name;
        $returnData['college_name'] = $this->user->professor_detail->college_name;
        $returnData['education_qualification'] = $this->user->professor_detail->education_qualification;
        $returnData['research_of_expertise'] = $this->user->professor_detail->research_of_expertise;
        $returnData['achievements'] = $this->user->professor_detail->achievements;
        $returnData['preferred_language'] = $this->user->professor_detail->preferred_language;
        $returnData['other_information'] = $this->user->professor_detail->other_information;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['title'] = $this->subject->title;
        $returnData['city']['uuid'] = $this->user->city_detail->id;
        $returnData['city']['title'] = $this->user->city_detail->name;
        $returnData['full_image_path'] = $this->user->full_image_path;
        if(in_array($this->subject->uuid,$existingSubjects)){
            $returnData['subject']['is_purchased'] = 1;
        }else{
            $returnData['subject']['is_purchased'] = 0;
        }

        return $returnData;      }
}
