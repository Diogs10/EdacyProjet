<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EtudiantResource extends JsonResource
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
            "id"=> $this->id,
            "nom"=> $this->nom,
            "prenom"=> $this->prenom,
            "email"=> $this->email,
            "telephone"=> $this->telephone,
            "photo"=> $this->photo,
            "date_naissance"=> $this->date_naissance,
            "lieu_naissance"=> $this->lieu_naissance,
        ];
    }
}
