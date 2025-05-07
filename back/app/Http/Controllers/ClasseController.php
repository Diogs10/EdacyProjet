<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClasseResource;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnnee;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    //
    public function all($id){
        $data = [];
        $classes = ClasseAnnee::where(['annee_scolaire_id'=>$id])->pluck('classe_id');
        for ($i=0; $i < count($classes); $i++) { 
            array_push($data,Classe::where('id',$classes[$i])->first());
        }
        return response()->json([
            "statu"=>true,
            "message"=>"les classes",
            "data"=>ClasseResource::collection($data)
        ]);
    }

    public function allClasseAnnee() {
        return response()->json([
            "statu"=>true,
            "message"=>"Les classes par année",
            "data"=>ClasseAnnee::all()
        ]);
    }

    public function register(Request $request) {
        $allClasses = Classe::all();
        for ($i=0; $i < count($allClasses); $i++) { 
            if (($request->filiere == $allClasses[$i]->filiere) && ($request->niveau == $allClasses[$i]->niveau)) {
                return response()->json([
                    "statu"=>false,
                    "message"=>"Cette classe existe déjà",
                    "data"=>[]
                ]);
            }
        }
        $classe = new Classe();
        $classe->filiere = $request->filiere;
        $classe->niveau = $request->niveau;
        $classe->save();
        return response()->json([
            "statu"=>true,
            "message"=>"Insertion classe réussi",
            "data"=>[$classe]
        ]);
    }

    public function PlanificationClasse(Request $request,$id) {
        for ($i=0; $i < count($request->classes); $i++) { 
            $claAnn = new ClasseAnnee();
            $claAnn->classe_id = $request->classes[$i];
            $claAnn->annee_scolaire_id = $id;
            $claAnn->effectif = 0;
            $claAnn->save();
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Planification réussi"
        ]);
    }

    public function nonPlanifier($id) {
        if ($id == "0") {
            $an = AnneeScolaire::all();
            if (count($an) == 0) {
                return response()->json([
                    "statu"=>false,
                    "message"=>"Aucun année ajoutée",
                    "data"=>[]
                ]);
            }
            $id = $an[0]->id;
        }
        $allClasseId = ClasseAnnee::where(['annee_scolaire_id'=>$id])->get();
        if (count($allClasseId) == 0) {
            return response()->json([
                "statu"=>true,
                "message"=>"Voici les classes pas encore planifiés pour cette année",
                "data"=>ClasseResource::collection(Classe::all())
            ]);
        }
        $idClassePlanifier = [];
        for ($i=0; $i < count($allClasseId); $i++) { 
            array_push($idClassePlanifier,$allClasseId[$i]->classe_id);
        }
        $recupAllClasse = Classe::all();
        if (count($recupAllClasse) == 0) {
            return response()->json([
                "statu"=>true,
                "message"=>"Vous n'avez pas de classe,veuillez insérer",
                "data"=>[]
            ]);
        }
        $recupAllClasseId = [];
        for ($j=0; $j < count($recupAllClasse); $j++) { 
            array_push($recupAllClasseId,$recupAllClasse[$j]->id);
        }
        $diffTableau1 = array_diff($recupAllClasseId, $idClassePlanifier);
        $diffTableau2 = array_diff($idClassePlanifier, $recupAllClasseId);

        // Fusionner les valeurs différentes des deux tableaux
        $resultat = array_merge($diffTableau1, $diffTableau2);
        if (count($resultat) == 0) {
            return response()->json([
                "statu"=>true,
                "message"=>"Tous les classes ont été planifié pour cette année",
                "data"=>[]
            ]);
        }
        $allClassesNonPlan = [];
        for ($f=0; $f < count($resultat); $f++) { 
            $p = Classe::where(['id'=>$resultat[$f]])->first();
            if ($p != null) {
                array_push($allClassesNonPlan,new ClasseResource($p));
            }
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Tous les classes n'ont été planifié pour cette année",
            "data"=>$allClassesNonPlan
        ]);
    }

    public function allClasse() {
        $classes = Classe::all();
        if (count($classes) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Aucune classe existe",
                "data"=>[]
            ]);
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Voici tous les classes",
            "data"=>ClasseResource::collection($classes)
        ]);
    }
}
