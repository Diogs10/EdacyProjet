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
}
