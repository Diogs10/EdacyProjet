<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CoursResource extends JsonResource
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
			"heureTotal"=> $this->heureTotal,
			"semestre_id"=> new SemestreResource($this->semestre),
			"module_prof_id"=> [
                'module'=>$this->moduleProf->module->libelle,
                'professeur'=>[
                    'nom'=>$this->moduleProf->user->nom,
                    'prenom'=>$this->moduleProf->user->prenom,
                    ]
                ],
            "termine"=> $this->termine,
            "classes"=> ClasseAnneeResource::collection($this->classeAnnee)
        ];
    }
}
