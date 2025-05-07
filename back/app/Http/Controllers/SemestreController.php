<?php

namespace App\Http\Controllers;

use App\Http\Requests\SemestreRequest;
use App\Http\Resources\SemestreResource;
use App\Models\Semestre;
use Illuminate\Http\Request;

class SemestreController extends Controller
{
    //
    public function all() {
        return response()->json([
            "statu"=>true,
            "message"=>"les semestres",
            "data"=>SemestreResource::collection(Semestre::all())
        ]);
    }

    public function register(SemestreRequest $request) {
        try {
            $semestre = new Semestre();
            $semestre->libelle = $request->libelle;
            $semestre->save();
            return response()->json([
                "statu"=>true,
                "message"=>"Insertion semestre réussi",
                "data"=>$semestre
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
