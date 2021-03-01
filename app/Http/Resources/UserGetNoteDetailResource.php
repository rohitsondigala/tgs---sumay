<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGetNoteDetailResource extends JsonResource
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
        $returnData['stream']['uuid'] = $this->stream->uuid;
        $returnData['stream']['title'] = $this->stream->title;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['title'] = $this->subject->title;
        $returnData['image_files'] = UserGetNotesFilesResource::collection($this->image_files)->toArray($request);
        $returnData['pdf_files'] = UserGetNotesFilesResource::collection($this->pdf_files)->toArray($request);
        $returnData['audio_files'] = UserGetNotesFilesResource::collection($this->audio_files)->toArray($request);
        return $returnData;
    }
}
