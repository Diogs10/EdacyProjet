<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClasseAnneeResource extends JsonResource
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
            "classe_id"=> new ClasseResource($this->classe),
            "annee_scolaire_id"=> new AnneeScolaireResource($this->annee),
            "effectif"=> $this->effectif,
        ];
    }
}
