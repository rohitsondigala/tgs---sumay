<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGetNotesResource extends JsonResource
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
        $returnData['uuid'] = $this->uuid;
        $returnData['slug'] = $this->slug;
        $returnData['title'] = $this->title;
        $returnData['date'] = Carbon::parse($this->created_at)->format('d M Y');
        $returnData['description'] = $this->description;
        $returnData['user']['uuid'] = $this->user->uuid;
        $returnData['user']['name'] = $this->user->name;
        $returnData['user']['role'] = $this->user->role->title;
        $returnData['user']['full_image_path'] = $this->user->full_image_path;
        if($this->user->role->title == 'STUDENT'){
            $returnData['user']['university_name'] =  !empty($this->user->student_detail) ? $this->user->student_detail->university_name : null;
            $returnData['user']['college_name'] =  !empty($this->user->student_detail) ? $this->user->student_detail->college_name : null;
            $returnData['user']['preferred_language'] =  !empty($this->user->student_detail) ? $this->user->student_detail->preferred_language : null;
            $returnData['user']['other_information'] =  !empty($this->user->student_detail) ? $this->user->student_detail->other_information : null;
            $returnData['user']['achievements'] =  null;
            $returnData['user']['research_of_expertise'] = null;
            $returnData['user']['education_qualification'] =  null;
            $returnData['user']['rating'] = 0;
            $returnData['user']['is_review'] = 0;
            $returnData['user']['total_reviews'] = 0;
            $returnData['user']['total_notes'] = 0;
            $returnData['user']['total_queries'] = 0;
        }else{
            $returnData['user']['university_name'] =  !empty($this->user->professor_detail) ? $this->user->professor_detail->university_name : null;
            $returnData['user']['college_name'] =  !empty($this->user->professor_detail) ? $this->user->professor_detail->college_name : null;
            $returnData['user']['preferred_language'] =  !empty($this->user->professor_detail) ? $this->user->professor_detail->preferred_language : null;
            $returnData['user']['other_information'] =  !empty($this->user->professor_detail) ? $this->user->professor_detail->other_information : null;
            $returnData['user']['achievements'] =  !empty($this->user->professor_detail) ? $this->user->professor_detail->achievements : null;
            $returnData['user']['research_of_expertise'] =  !empty($this->user->professor_detail)  ? $this->user->professor_detail->research_of_expertise : null;
            $returnData['user']['education_qualification'] = !empty($this->user->professor_detail)  ? $this->user->professor_detail->education_qualification : null;
            $returnData['user']['rating'] = 0;
            $returnData['user']['is_review'] = 0;
            $returnData['user']['total_reviews'] = 0;
            $returnData['user']['total_notes'] = 0;
            $returnData['user']['total_queries'] = 0;
        }
        $returnData['stream']['uuid'] = $this->stream->uuid;
        $returnData['stream']['title'] = $this->stream->title;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['title'] = $this->subject->title;
        $returnData['image_files'] = UserGetNotesFilesResource::collection($this->image_files)->toArray($request);
        $returnData['pdf_files'] = UserGetNotesFilesResource::collection($this->pdf_files)->toArray($request);
        $returnData['audio_files'] = UserGetNotesFilesResource::collection($this->audio_files)->toArray($request);
        if(in_array($this->subject->uuid,$existingSubjects)){
            $returnData['subject']['is_purchased'] = 1;
        }else{
            $returnData['subject']['is_purchased'] = 0;
        }
        return $returnData;
    }
}
