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
