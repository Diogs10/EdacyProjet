<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id"=>$this->id,
            "cours_id"=>new CoursResource($this->cours),
            "salle_id"=>new SalleResource($this->salle),
            "heureDebut"=>$this->heureDebut,
            "duree"=>$this->duree,
            "date"=>$this->date,
            "etat"=>$this->etat
        ];
    }
}
