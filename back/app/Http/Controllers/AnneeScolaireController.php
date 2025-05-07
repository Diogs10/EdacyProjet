<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnneeScolaireRequest;
use App\Models\AnneeScolaire;
use Illuminate\Http\Request;

class AnneeScolaireController extends Controller
{
    //
    public function all(){
        $allId = AnneeScolaire::all();
        if (count($allId) != 0) {
            for ($i=0; $i < count($allId); $i++) { 
                AnneeScolaire::where(['id'=>$allId[$i]->id])->update(['etat'=>'0']);
            }
            AnneeScolaire::where(['id'=>$allId[0]->id])->update(['etat'=>'1']);
        }
        return response()->json([
            "statu"=>true,
            "message"=>"les années",
            "data"=>AnneeScolaire::all()
        ]);
    }

    public function updateEtat($id) {
        $allId = AnneeScolaire::all();
        if (count($allId) != 0) {
            for ($i=0; $i < count($allId); $i++) { 
                AnneeScolaire::where(['id'=>$allId[$i]->id])->update(['etat'=>'0']);
            }  
        }
        AnneeScolaire::where(['id'=>$id])->update(['etat'=>'1']);
        return response()->json([
            "statu"=>true,
            "message"=>"Session validé",
            "data"=>[]
        ]);
    }

    public function register(AnneeScolaireRequest $request){
        $annees = explode("-", $request->libelle);
        if (count($annees) == 2) {
            $diff = $annees[1]-$annees[0];
            if ($diff != 1) {
                return response()->json([
                    "statu"=>false,
                    "messages" => "La difference entre les annees est 1 année",
                    "data"=>[]
                ]);
            }
            $annee = new AnneeScolaire();
            $annee->libelle = $request->libelle;
            $annee->etat = '0';
            $annee->save();
            return response()->json([
                "statu"=>true,
                "message"=>"Insertion annee réussi",
                "data"=>[$annee]
            ]);
        }
        return response()->json([
            "statu"=>false,
            "messages" => 'Le format de l\'année est annéeDébut-annéeFin',
            "data"=>[]
        ]); 
    }
}
