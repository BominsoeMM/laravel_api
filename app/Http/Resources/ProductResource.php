<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function stockStatus($count){
        $status = "";
        if ($count > 10){
            $status = "available";
        }
        elseif ($count < 10){
            $status = "few";
        }
        elseif ($count === 0 ){
            $status = "Out of stock";
        }
        return $status;
    }
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "price" => $this->price,
            "show_price" => $this->price . " MMK",
            "stock" => $this->stock,
            "stock_Status" => $this->stockStatus($this->stock),
            "date" => $this->created_at->format("H:i A"),
            "time" => $this->created_at->format("d M Y"),
//            "user" => $this->user->name
            "owner" => new UserResource($this->user),
            "photo" => PhotoResource::collection($this->photos)
        ];
    }
}
