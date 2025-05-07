<?php

namespace App\Http\Controllers;

use App\Http\Resources\CoursResource;
use App\Models\AnneeScolaire;
use App\Models\ClasseAnnee;
use App\Models\ClasseAnneeCours;
use App\Models\Cours;
use App\Models\Inscription;
use App\Models\ModuleProf;
use Illuminate\Http\Request;

class CoursController extends Controller
{
    /*
    *cette fonction permet de filter :
    **  Tous les cours
    **  Cours Terminé
    **  Cours qui restent des heures
    */
    public function all($id,$etat) {

        //année par défaut
        if ($id == "0") {
            $annees = AnneeScolaire::all();
            if (count($annees) == 0) {
                return response()->json([
                    "statu"=>false,
                    "message"=>"Aucune année n'est enregistrée",
                    "data"=>[]
                ]);
            }
            $id = $annees[0]->id;
        }

        //si l'année est donnée
        //Vérification de l'année a-t-il des classes
        $classeAnnee_id = ClasseAnnee::where(['annee_scolaire_id'=>$id])->pluck('id');
        if (count($classeAnnee_id) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Cette année n'a pas de classe planifié",
                "data"=>[]
            ]);
        }
        $allCours_id = [];
        for ($h=0; $h < count($classeAnnee_id); $h++) { 
            $cc = ClasseAnneeCours::where(['classe_annee_id'=>$classeAnnee_id[$h]])->get();
            if (count($cc) != 0) {
                for ($k=0; $k < count($cc); $k++) { 
                    array_push($allCours_id,$cc[$k]->cours_id);
                }
            }
        }

        //Vérification si cette année ont a programmé des cours
        if (count($allCours_id) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Cette année n'a pas de cours",
                "data"=>[]
            ]);
        }
        
        //Ici on enléve les doublons parcequ'il y'a une table association entre classeAnnee et cours
        //Il se peut l'ID cours se touve plusieurs fois dans le tableau
        //Exemple : CIA et CIB de meme annee fait un cours ensemble donc l'ID cours ont le retouve 2 fois dans ce tableau
        $allCours = [];
        $unique = array_unique($allCours_id);

        //Verification des etats
        //2:Tous les cours
        if ($etat == "2") {
            for ($i=0; $i < count($allCours_id); $i++) { 

        //Dans un tableau ["1"=>1,"2"=>2,"3"=>1,"4"=>4],avec array_unique on arra ["1"=>1,"2"=>2,"4"=>4]
        //Dans ce cas on a l'indice 3 qui est sauté,pour parcourir il faut tester l'existant du key sinon ça ne marche pas
        //d'ou l'importance de array_key 
                if (array_key_exists($i,$unique)) {
                    $c = Cours::where(['id'=>$allCours_id[$i]])->first();
                    if ($c != null) {
                        array_push($allCours,new CoursResource($c));
                    }  
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici les cours",
                "data"=>$allCours
            ]); 
        }

        //1:Terminé
        if($etat == "1"){
            for ($i=0; $i < count($allCours_id); $i++) { 
                if (array_key_exists($i,$unique)) {
                    $c = Cours::where(['termine'=>$etat,'id'=>$allCours_id[$i]])->first();
                    if ($c != null) {
                        array_push($allCours,new CoursResource($c));
                    }  
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici les cours terminé",
                "data"=>$allCours
            ]);
        }

        //0:En cours
        if($etat == "0"){
            for ($i=0; $i < count($allCours_id); $i++) { 
                if (array_key_exists($i,$unique)) {
                    $c = Cours::where(['termine'=>$etat,'id'=>$allCours_id[$i]])->first();
                    if ($c != null) {
                        array_push($allCours,new CoursResource($c));
                    }  
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici les cours qui restent",
                "data"=>$allCours
            ]);
        }
    }

    /*
    *cette fonction permet :
    **  Enregister les cours
    */
    public function register(Request $request){
        try {
            //Vérification de la selection des classes
            if (count($request->classes) == 0) {
                return response()->json([
                    "statu"=>false,
                    "message"=>"Choississez des classes",
                    "data"=>[]
                ]);
            }
            //Recupération de toutes les cours des classes choissies
            $tab = [];
            for ($i=0; $i < count($request->classes); $i++) { 
                $result = ClasseAnneeCours::where(['classe_annee_id'=>$request->classes[$i]])->get();
                if (count($result) != 0) {
                    for ($j=0; $j < count($result); $j++) { 
                        array_push($tab,$result[$j]->cours_id);
                    }    
                }
            }

            //Recupération de toutes les moduleProfs
            $allSemestreCoursId = [];
            $unique = array_unique($tab);
            $allModuleCoursId = [];
            if (count($tab) != 0) {
                for ($d=0; $d < count($tab); $d++) { 
                    if (array_key_exists($d,$unique)) {
                        array_push($allModuleCoursId,Cours::where(['id'=>$unique[$d]])->first()->module_prof_id);
                        array_push($allSemestreCoursId,Cours::where(['id'=>$unique[$d]])->first()->semestre_id);
                    }
                }   
            }
            $allModuleId = [];
            if (count($allModuleCoursId) != 0) {
                for ($m=0; $m < count($allModuleCoursId); $m++) { 
                    array_push($allModuleId,ModuleProf::where(['id'=>$allModuleCoursId[$m]])->first()->module_id);
                }
            }
            if (count($allModuleId) != 0) {
                for ($c=0; $c < count($allModuleId); $c++) { 
                    if (($allModuleId[$c] == $request->module_id) && ($allSemestreCoursId[$c] == $request->semestre_id)) {
                        return response()->json([
                            "statu"=>false,
                            "message"=>"Une des classes a déjà cette module",
                            "data"=>[]
                        ]);
                    }
                }
            }
            $modProf = ModuleProf::where(['module_id'=>$request->module_id,'user_id'=>$request->professeur_id])->first();
            $cours = new Cours();
            $cours->heureTotal = $request->heureTotal;
            $cours->semestre_id = $request->semestre_id;
            $cours->module_prof_id = $modProf->id;
            $cours->termine = "0";
            $cours->save();
            return response()->json([
                "statu"=>true,
                "message"=>"Insertion cours reussie",
                "data"=>[new CoursResource($cours)]
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /*
    *cette fonction permet de filter des etudiants:
    **  Tous les cours
    **  Cours Terminé
    **  Cours qui restent des heures
    */
    public function byEtudiant($an,$id,$etat) {
        //etudiants
        $claAn = ClasseAnnee::where(['annee_scolaire_id'=>$an])->pluck('id');
        if (count($claAn) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Cette année n'est pas associée à une classe",
                "data"=>[]
            ]);
        }
        $claANN = 0;
        for ($i=0; $i < count($claAn); $i++) { 
            $inscrit = Inscription::where(['classe_annee_id'=>$claAn[$i],'user_id'=>$id])->first();
            if ($inscrit != null) {
                $claANN = $claAn[$i];
                break;
            }
        }
        if ($inscrit == null) {
            return response()->json([
                "statu"=>false,
                "message"=>"Vous n'etes pas inscrit cette année",
                "data"=>[]
            ]);
        }
        $allCours = ClasseAnneeCours::where(['classe_annee_id'=>$claANN])->pluck('cours_id');
        if (count($allCours) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Aucun cours n'est programmée pour votre classe",
                "data"=>[]
            ]);
        }
        $coursEtudiants = [];
        if($etat == "1"){
            for ($i=0; $i < count($allCours); $i++) { 
                $c = Cours::where(['termine'=>$etat,'id'=>$allCours[$i]])->first();
                if ($c != null) {
                    array_push($coursEtudiants,new CoursResource($c));
                }  
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tes cours terminé",
                "data"=>$coursEtudiants
            ]);
        }
        if($etat == "0"){
            for ($i=0; $i < count($allCours); $i++) { 
                $c = Cours::where(['termine'=>$etat,'id'=>$allCours[$i]])->first();
                if ($c != null) {
                    array_push($coursEtudiants,new CoursResource($c));
                }  
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tes cours qui reste",
                "data"=>$coursEtudiants
            ]);
        }
        if($etat == "2"){
            for ($i=0; $i < count($allCours); $i++) { 
                $c = Cours::where(['id'=>$allCours[$i]])->first();
                if ($c != null) {
                    array_push($coursEtudiants,new CoursResource($c));
                }  
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tous tes cours",
                "data"=>$coursEtudiants
            ]);
        }
    }

    /*
    *cette fonction permet de filter des professeur:
    **  Tous les cours
    **  Cours Terminé
    **  Cours qui restent des heures
    */
    public function byProfesseur($an,$id,$etat) {
        // $allModuleProf = Cours::all()
        $allModuleProf = ModuleProf::where(['user_id'=>$id])->pluck('id');
        if (count($allModuleProf) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Ce professeur n'a pas module assignée",
                "data"=>[]
            ]);
        }
        $allCours = [];
        for ($i=0; $i < count($allModuleProf); $i++) { 
            $f = Cours::where(['module_prof_id'=>$allModuleProf[$i]])->get();
            if (count($f) != 0) {
                for ($j=0; $j < count($f); $j++) { 
                    array_push($allCours,$f[$j]->id);
                }
            }
        }
        if (count($allCours) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Vous n'avez pas de cours",
                "data"=>[]
            ]);
        }
        $classeAnnee_id = ClasseAnnee::where(['annee_scolaire_id'=>$an])->pluck('id');
        if (count($classeAnnee_id) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Vous n'avez pas de cours cette année",
                "data"=>[]
            ]);
        }
        $COURS_ID = [];
        for ($g=0; $g < count($classeAnnee_id); $g++) { 
            $p = ClasseAnneeCours::where(['classe_annee_id'=>$classeAnnee_id[$g]])->pluck('cours_id');
            if (count($p) != 0) {
                for ($q=0; $q < count($p); $q++) { 
                    array_push($COURS_ID,$p[$q]);
                }
            }
        }
        $tab = array_unique($COURS_ID);
        $interCours = array_intersect($tab,$allCours);
        $coursProf = [];
        if($etat == "1"){
            for ($i=0; $i < count($COURS_ID); $i++) { 
                if (array_key_exists($i,$interCours)) {
                    $c = Cours::where(['termine'=>$etat,'id'=>$interCours[$i]])->first();
                    if ($c != null) {
                        array_push($coursProf,new CoursResource($c));
                    }    
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tes cours terminé",
                "data"=>$coursProf
            ]);
        }
        if($etat == "0"){
            for ($i=0; $i < count($COURS_ID); $i++) { 
                if (array_key_exists($i,$interCours)) {
                    $c = Cours::where(['termine'=>$etat,'id'=>$interCours[$i]])->first();
                    if ($c != null) {
                        array_push($coursProf,new CoursResource($c));
                    }  
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tes cours qui reste",
                "data"=>$coursProf
            ]);
        }
        if($etat == "2"){
            for ($i=0; $i < count($COURS_ID); $i++) { 
                if (array_key_exists($i,$interCours)) {
                    $c = Cours::where(['id'=>$interCours[$i]])->first();
                    if ($c != null) {
                        array_push($coursProf,new CoursResource($c));
                    }  
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tous tes cours",
                "data"=>$coursProf
            ]);
        }
    }
}
