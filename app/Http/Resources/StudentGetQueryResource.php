<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentGetQueryResource extends JsonResource
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
        $returnData['slug'] = $this->slug;
        $returnData['title'] = $this->title;
        $returnData['date'] = Carbon::parse($this->created_at)->format('d M Y');
        $returnData['description'] = $this->description;
        $returnData['user']['uuid'] = $this->to_user->uuid;
        $returnData['user']['name'] = $this->to_user->name;
        $returnData['user']['role'] = $this->to_user->role->title;
        $returnData['user']['full_image_path'] = $this->to_user->full_image_path;
        $returnData['stream']['uuid'] = $this->stream->uuid;
        $returnData['stream']['title'] = $this->stream->title;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['title'] = $this->subject->title;
        if($this->post_reply()->count() > 0){
            $returnData['is_replied'] = 1;
            $returnData['reply_data']['uuid'] = $this->post_reply->uuid;
            $returnData['reply_data']['description'] = $this->post_reply->description;
            $returnData['reply_data']['image_files'] = ProfessorGetQueryReplyFilesResource::collection($this->post_reply->image_files)->toArray($request);
            $returnData['reply_data']['pdf_files'] = ProfessorGetQueryReplyFilesResource::collection($this->post_reply->pdf_files)->toArray($request);
            $returnData['reply_data']['audio_files'] = ProfessorGetQueryReplyFilesResource::collection($this->post_reply->audio_files)->toArray($request);

        }else{
            $returnData['is_replied'] = 0;
            $returnData['reply_data'] =null;
        }
        $returnData['image_files'] = ProfessorGetQueryFilesResource::collection($this->image_files)->toArray($request);
        $returnData['pdf_files'] = ProfessorGetQueryFilesResource::collection($this->pdf_files)->toArray($request);
        $returnData['audio_files'] = ProfessorGetQueryFilesResource::collection($this->audio_files)->toArray($request);
        return $returnData;    }
}
