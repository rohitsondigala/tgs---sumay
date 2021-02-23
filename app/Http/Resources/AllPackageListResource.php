<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AllPackageListResource extends JsonResource
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
        $returnData['description'] = $this->description;
        $returnData['price_month_3'] = $this->price_month_3;
        $returnData['price_month_3_days'] = Carbon::now()->addMonths(3)->diffInDays();
        $returnData['price_month_6'] = $this->price_month_6;
        $returnData['price_month_6_days'] = Carbon::now()->addMonths(6)->diffInDays();
        $returnData['price_month_12'] = $this->price_month_12;
        $returnData['price_month_12_days'] = Carbon::now()->addMonths(12)->diffInDays();
        $returnData['price_month_24'] = $this->price_month_24;
        $returnData['price_month_24_days'] = Carbon::now()->addMonths(24)->diffInDays();
        $returnData['price_month_36'] = $this->price_month_36;
        $returnData['price_month_36_days'] = Carbon::now()->addMonths(36)->diffInDays();
        $returnData['full_image_path'] = $this->full_image_path;
        $returnData['stream']['id'] = $this->stream->uuid;
        $returnData['stream']['title'] = $this->stream->title;
        $returnData['subject']['uuid'] = $this->subject->uuid;
        $returnData['subject']['title'] = $this->subject->title;
        return $returnData;
    }


}
