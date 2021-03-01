<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserGetNotesFilesResource extends JsonResource
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
        $returnData['file_name'] = $this->file_name;
        $returnData['file_type'] = $this->file_type;
        $returnData['file_mime_type'] = $this->file_mime_type;
        $returnData['file_size'] = $this->file_size;
        $returnData['file_path'] = $this->full_path;
        return $returnData;
    }
}
