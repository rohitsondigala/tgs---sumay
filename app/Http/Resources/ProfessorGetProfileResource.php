<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessorGetProfileResource extends JsonResource
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
        $returnData['success'] = true;
        $returnData['message'] = 'User Detail';
        $returnData['data']['uuid'] = $this->uuid;
        $returnData['data']['name'] = $this->name;
        $returnData['data']['mobile'] = $this->mobile;
        $returnData['data']['email'] = $this->email;
        $returnData['data']['full_image_path'] = $this->full_image_path;
        $returnData['data']['university_name'] = $this->professor_detail->university_name;
        $returnData['data']['college_name'] = $this->professor_detail->college_name;
        $returnData['data']['preferred_language'] = $this->professor_detail->preferred_language;
        $returnData['data']['other_information'] = $this->professor_detail->other_information;
        $returnData['data']['achievements'] = $this->professor_detail->achievements;
        $returnData['data']['research_of_expertise'] = $this->professor_detail->research_of_expertise;
        $returnData['data']['education_qualification'] = $this->professor_detail->education_qualification;
        $returnData['data']['country']['id'] = $this->country_detail->id;
        $returnData['data']['country']['name'] = $this->country_detail->name;
        $returnData['data']['state']['id'] = $this->state_detail->id;
        $returnData['data']['state']['name'] = $this->state_detail->name;
        $returnData['data']['city']['id'] = $this->city_detail->id;
        $returnData['data']['city']['name'] = $this->city_detail->name;
        $returnData['data']['stream']['uuid'] = $this->stream->uuid;
        $returnData['data']['stream']['title'] = $this->stream->title;


        if (!empty($this->professor_subjects) && count($this->professor_subjects)) {
            $i = 0;
            foreach ($this->professor_subjects as $subject) {
                $i++;
                $returnData['data']['subjects'][$i]['uuid'] = $subject->subject->uuid;
                $returnData['data']['subjects'][$i]['title'] = $subject->subject->title;
            }
        }else{
            $returnData['data']['subjects'] =[];
        }

        return $returnData;
    }

}
