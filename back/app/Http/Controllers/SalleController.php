<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalleRequest;
use App\Http\Resources\SalleResource;
use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function register(SalleRequest $request) {
        try {
            $salle = new Salle();
            $salle->nom = $request->nom;
            $salle->effectif = $request->effectif;
            $salle->save();
            return response()->json([
                "statu"=>true,
                "message"=>"Insertion salle réussi",
                "data"=>[new SalleResource($salle)]
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function all() {
        return response()->json([
            "statu"=>true,
            "message"=>"Tous les salles",
            "data"=>SalleResource::collection(Salle::all())
        ]);
    }

    public function update(SalleRequest $request, $id)
    {
        try {
            // Chercher la salle par ID
            $salle = Salle::find($id);

            // Vérification de l'existence
            if (!$salle) {
                return response()->json([
                    "statu" => false,
                    "message" => "Salle non trouvée"
                ], 404);
            }

            // Mise à jour des données
            $salle->update([
                'nom' => $request->nom,
                'effectif' => $request->effectif,
            ]);

            return response()->json([
                "statu" => true,
                "message" => "Modification de la salle réussie",
                "data" => new SalleResource($salle)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "statu" => false,
                "message" => "Erreur lors de la mise à jour",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function delete( $id) {
        Salle::where(['id'=>$id])->delete();
        return response()->json([
            "statu"=>true,
            "message"=>"Salle supprimée",
            "data"=>[]
        ]);
    }
}
