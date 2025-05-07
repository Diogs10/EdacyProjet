<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClasseResource;
use App\Http\Resources\EtudiantResource;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\ProfesseurResource;
use App\Http\Resources\UserResource;
use App\Imports\UsersImport;
use App\Mail\SendEmails;
use App\Models\Classe;
use App\Models\ClasseAnnee;
use App\Models\ClasseAnneeCours;
use App\Models\Cours;
use App\Models\Inscription;
use App\Models\Module;
use App\Models\ModuleProf;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    public function all() {
        return response()->json([
            "statu"=>true,
            "message"=>"les utilisateurs",
            "data"=>UserResource::collection(User::all())
        ]);
    }
/*______________________________________PROFESSEURS________________________________________*/
    public function byModule($id){
        $data = [];
        $classes = ModuleProf::where(['module_id'=>$id])->pluck('user_id');
        for ($i=0; $i < count($classes); $i++) { 
            array_push($data,User::where('id',$classes[$i])->first());
        }
        return response()->json([
            "message"=>"les prfofesseurs de ce module",
            "data"=>ProfesseurResource::collection($data)
        ]);
    }

    public function registerProf(Request $request){
        $this->authorize('create',User::class);
        $prof = new User();
        $prof->nom = $request->nom;
        $prof->prenom = $request->prenom;
        $prof->email = $request->email;
        $prof->telephone = $request->telephone;
        $prof->photo = null;
        $prof->password = Hash::make('1234');
        $prof->role_id = 2;
        $prof->grade = $request->grade;
        $prof->specialite = $request->specialite;
        $prof->save();
        for ($i=0; $i < count($request->modules); $i++) { 
            $moduleProf = new ModuleProf();
            $moduleProf->module_id = $request->modules[$i];
            $moduleProf->user_id = $prof->id;
            $moduleProf->save();
        }
        Mail::to($prof->email)->send(new SendEmails($prof->prenom));
        return response()->json([
            "statu"=>true,
            "message"=>"Insertion professeur réussi",
            "data"=>[new ProfesseurResource($prof)]
        ]);
    }

    public function allProf() {
        $allProf = User::where(['role_id'=>2])->get();
        if (count($allProf) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Aucune Prof",
                "data"=>[]
            ]);
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Tous les profs",
            "data"=>ProfesseurResource::collection($allProf)
        ]);
    }

    public function nbreHeureMois($id,$module){
        if ($module == "0") {
            $alModuleProfsId = ModuleProf::where(['user_id'=>$id])->get();
            if (count($alModuleProfsId) == 0) {
                return response()->json([
                    "statu"=>false,
                    "message"=>"Vous n'avez pas de module",
                    "data"=>[0]
                ]);
            }
            $idCours = [];
            for ($i=0; $i < count($alModuleProfsId); $i++) { 
                $a = Cours::where(['module_prof_id'=>$alModuleProfsId[$i]->id])->get();
                if (count($a) != 0) {
                    for ($j=0; $j < count($a); $j++) { 
                        array_push($idCours,$a[$j]->id);
                    }
                }
            }
            if (count($idCours) == 0) {
                return response()->json([
                    "statu"=>false,
                    "message"=>"Vous n'avez pas de cours",
                    "data"=>[0]
                ]);
            }
            $nbreHeure = 0;
            // dd(getdate()["mon"]);
            for ($f=0; $f < count($idCours); $f++) { 
                $b = Session::where(['cours_id'=>$idCours[$f],'etat'=>"1"])->get();
                if (count($b) != 0) {
                    for ($h=0; $h < count($b); $h++) { 
                        if ($b != null) {
                            $l = explode("-",$b[$h]->date);
                            if (intval($l[1]) == getdate()["mon"]) {
                                $nbreHeure = $nbreHeure + $b[$h]->duree;
                            }
                        }
                    }  
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Le nombre d'heures faites durant ce mois",
                "data"=>[$nbreHeure]
            ]); 
        }
        $alModuleProfsId = ModuleProf::where(['user_id'=>$id,"module_id"=>$module])->first();
        if ($alModuleProfsId == null) {
            return response()->json([
                "statu"=>false,
                "message"=>"Vous n'avez pas de module",
                "data"=>[0]
            ]);
        }
        $idCours = [];
        $a = Cours::where(['module_prof_id'=>$alModuleProfsId->id])->get();
        if (count($a) != 0) {
            for ($j=0; $j < count($a); $j++) { 
                array_push($idCours,$a[$j]->id);
            }
        }
        if (count($idCours) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Vous n'avez pas de cours",
                "data"=>[0]
            ]);
        }
        $nbreHeure = 0;
        for ($f=0; $f < count($idCours); $f++) { 
            $b = Session::where(['cours_id'=>$idCours[$f],'etat'=>"1"])->get();
            if (count($b) != 0) {
                for ($h=0; $h < count($b); $h++) { 
                    if ($b != null) {
                        $l = explode("-",$b[$h]->date);
                        if (intval($l[1]) == getdate()["mon"]) {
                            $nbreHeure = $nbreHeure + $b[$h]->duree;
                        }
                    }
                }  
            }
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Le nombre d'heures faites durant ce mois sur ce module",
            "data"=>[$nbreHeure]
        ]); 
    }

    public function modulebyProf($id) {
        $allModuleId = ModuleProf::where(['user_id'=>$id])->pluck('module_id');
        if (count($allModuleId) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Vous n'avez pas de module",
                "data"=>[]
            ]);
        }
        $modules = [];
        for ($i=0; $i < count($allModuleId); $i++) { 
            array_push($modules,new ModuleResource(Module::where(['id'=>$allModuleId[$i]])->first()));
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Voici les modules qu'ils faient",
            "data"=>$modules
        ]);
    }
/*______________________________________ETUDIANTS__________________________________________*/
    public function import() {
        Excel::import(new UsersImport,request()->file('file'));
    }

    public function etudiantsByCours($id) {
        // $allEtudiants = [];
        // $allClasses =[];
        // $classeAnnee = ClasseAnneeCours::where(['cours_id'=>$id])->get();
        // for ($j=0; $j < count($classeAnnee); $j++) { 
        //     array_push($allClasses,ClasseAnnee::where(['id'=>$classeAnnee[$j]->classe_annee_id])->first());
        //     $etudiants_id = Inscription::where(['classe_annee_id'=>$classeAnnee[$j]->classe_annee_id])->pluck('user_id');
        //     $etudiants = [];
        //     for ($i=0; $i < count($etudiants_id); $i++) { 
        //         array_push($etudiants,new EtudiantResource(User::where(['id'=>$etudiants_id[$i]])->first()));
        //     }
        //     array_push($allEtudiants,$etudiants);
        // }
        // $CLASSES = [];
        // for ($n=0; $n < count($allClasses); $n++) { 
        //     array_push($CLASSES,Classe::where(['id'=>$allClasses[$n]->classe_id])->first());
        // }
        // return response()->json([
        //     "message"=>"Voici les étudiants de ce cours",
        //     "data"=>[$allEtudiants,ClasseResource::collection($CLASSES)]
        // ]);
        $allEtudiants = [];
        $classeAnnee = ClasseAnneeCours::where(['cours_id'=>$id])->get();
        for ($j=0; $j < count($classeAnnee); $j++) { 
            $a = ClasseAnnee::where(['id'=>$classeAnnee[$j]->classe_annee_id])->first();
            $b = Classe::where(['id'=>$a->classe_id])->first();
            $etudiants_id = Inscription::where(['classe_annee_id'=>$classeAnnee[$j]->classe_annee_id])->pluck('user_id');
            $etudiants = [];
            for ($i=0; $i < count($etudiants_id); $i++) { 
                array_push($etudiants,new EtudiantResource(User::where(['id'=>$etudiants_id[$i]])->first()));
            }
            array_push($allEtudiants,["classe"=>new ClasseResource($b),"etudiants"=>$etudiants]);
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Voici les étudiants de ce cours",
            "data"=>$allEtudiants
        ]);
    }

/*_________________________________________________________________________________________*/
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response([
                "statu" => false
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken("token")->plainTextToken;
        $cookie = cookie("token", $token, 24 * 60);
        return response([
            "statu" =>true,
            "token" => $token,
            "user"  => $user
        ],200)->withCookie($cookie);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout()
    {
        Auth::guard('sanctum')->user()->tokens()->delete();
        // Cookie::forget("token");
        return response([
            "statu"=>true,
            "message" => "success"
        ]);
    }
}