<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    //
    public function all() {
        $allModules = Module::all();
        if (count($allModules) == 0) {
            return response()->json([
                "statu"=>true,
                "message"=>"Les modules",
                "data"=>[]
            ]);
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Les modules",
            "data"=>ModuleResource::collection($allModules)
        ]);
    }

    public function register(ModuleRequest $request) {
        try {
            $module = new Module();
            $module->libelle = $request->libelle;
            $module->save();
            return response()->json([
                "statu"=>true,
                "message"=>"Insertion module réussi",
                "data"=>[new ModuleResource($module)]
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
