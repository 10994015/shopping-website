<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ProductResource extends JsonResource
{
    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'slug'=>$this->slug,
            'description'=>$this->description ?? '',
            'short_description'=>$this->short_description ?? '',
            'image_url'=>$this->image ?? '',
            'price'=>$this->price,
            'sale_price'=>$this->sale_price ?? '',
            'category'=> $this->category,
            'hidden'=>$this->hidden ? true : false,
            'featured'=>$this->featured ? true : false,
            'created_at'=>(new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at'=>(new \DateTime($this->updated_at))->format('Y-m-d H:i:s'),
        ];
    }
}
