<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Annulation;
use App\Models\ClasseAnnee;
use App\Models\ClasseAnneeCours;
use App\Models\Cours;
use App\Models\ModuleProf;
use App\Models\Salle;
use App\Models\Session;
use DateTime;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function all() {
        return response()->json([
            "statu"=>true,
            "message"=>"Tous les sessions",
            "data"=>SessionResource::collection(Session::all())
        ]);
    }

    public function register(Request $request,$id){
        $nbrHeure = 0;
        $allSession = Session::where(['cours_id'=>$id])->get();
        if (count($allSession) == 0) {
            $nbrHeure = 0;
        }
        else {
            for ($p=0; $p < count($allSession); $p++) { 
                $nbrHeure += $allSession[$p]->duree;
            }
        }
        $heureGlobal = Cours::where(['id'=>$id])->first()->heureTotal;
        if ($nbrHeure == $heureGlobal) {
            return response()->json([
                "statu"=>false,
                "message"=>"Cours terminé",
                "data"=>[]
            ]);
        }
        if (($heureGlobal - $nbrHeure) < $request->duree) {
            $heureRestant = $heureGlobal - $nbrHeure;
            return response()->json([
                "statu"=>false,
                "message"=>"Ce cours reste .$heureRestant h de temps ",
                "data"=>[]
            ]);
        }
        $heureFin = intval(explode('h',$request->heureDebut)[0])+intval(explode('h',$request->duree)[0]);
        $date = new DateTime($request->date);
        $sessions = Session::where(['etat'=>'0'])->get();
        for ($i=0; $i < count($sessions); $i++) { 
            if ($date == new DateTime($sessions[$i]->date)) {
                $heureFinn = intval(explode('h',$sessions[$i]->heureDebut)[0])+intval(explode('h',$sessions[$i]->duree)[0]);
                if ((intval(explode('h',$request->heureDebut)[0]) == intval(explode('h',$sessions[$i]->heureDebut)[0])) || ((intval(explode('h',$request->heureDebut)[0]) < intval(explode('h',$sessions[$i]->heureDebut)[0])) && ($heureFin > intval(explode('h',$sessions[$i]->heureDebut)[0]))) || ((intval(explode('h',$request->heureDebut)[0]) > intval(explode('h',$sessions[$i]->heureDebut)[0])) && (intval(explode('h',$request->heureDebut)[0]) < $heureFinn)) ) {
                    //verification presentiel ou en ligne
                    //verification de la disponibilite du salle
                    if ($request->type == false) {  
                        if ($request->salle_id == $sessions[$i]->salle_id) {
                            return response()->json([
                                "statu"=>false,
                                "message"=>"Salle Occupée",
                                "data"=>[]
                            ]);
                        }
                    }
                    //verification de la disponibilite du prof
                    $profMod = Cours::find($id)->module_prof_id;
                    $prof = ModuleProf::find($profMod)->user_id;
                    $coursProf = ModuleProf::where('user_id',$prof)->pluck('id');
                    $cours = Cours::find($sessions[$i]->cours_id);
                    for ($j=0; $j < count($coursProf); $j++) { 
                        if ($coursProf[$j] == $cours->module_prof_id) {
                            return response()->json([
                                "statu"=>false,
                                "message"=>"Prof Occupée",
                                "data"=>[]
                            ]);
                        }
                    }
                    $classes = ClasseAnneeCours::where(['cours_id'=>$id])->pluck('classe_annee_id');
                    if ($request->type == 1) {
                        //verification de la l'effectif total par rapport à une salle
                        $eff = 0;
                        for ($h=0; $h < count($classes); $h++) { 
                            $eff += ClasseAnnee::find($classes[$h])->effectif;
                        }
                        if ($eff > Salle::find($request->salle_id)->effectif) {
                            return response()->json([
                                "statu"=>false,
                                "message"=>"La salle est trop petite pour l'effectif",
                                "data"=>[]
                            ]);
                        }   
                    }
                    //les classes du cours en ce moment
                    $classesMoment = ClasseAnneeCours::where(['cours_id'=>$cours->id])->pluck('classe_annee_id');
                    for ($k=0; $k < count($classes); $k++) { 
                        for ($g=0; $g < count($classesMoment); $g++) { 
                            if ($classes[$k] == $classesMoment[$g]) {
                                return response()->json([
                                    "statu"=>false,
                                    "message"=>"Une ou les classes sont occupée",
                                    "data"=>[]
                                ]);
                            }  
                        }
                    }
                }
            }
        }
        if ($request->type == false) {
            $classes = ClasseAnneeCours::where(['cours_id'=>$id])->pluck('classe_annee_id');
            $eff = 0;
            for ($h=0; $h < count($classes); $h++) { 
                $eff += ClasseAnnee::find($classes[$h])->effectif;
            }
            if ($eff > Salle::find($request->salle_id)->effectif) {
                return response()->json([
                    "statu"=>false,
                    "message"=>"La salle est trop petite pour l'effectif",
                    "data"=>[]
                ]);
            }   
        }
        $session = new Session();
        $session->cours_id = $id;
        $session->salle_id = $request->salle_id;
        $session->heureDebut = $request->heureDebut;
        $session->duree = intval(explode('h',$request->duree)[0]);
        $session->date = $request->date;
        $session->type = $request->type;
        $session->etat = "0";
        $session->save();
        $nbrHeure = $nbrHeure + $request->duree;
        if ($nbrHeure == $heureGlobal) {
            Cours::where(['id'=>$id])->update(['termine' => "1"]);
        }
        return response()->json([
            "statu"=>true,
            "message"=>"insertion de session réussi",
            "data"=>$session
        ]);
    }

    public function byCours($id,$etat) {
        $allIdsession = Session::where(['cours_id'=>$id])->pluck('id');
        if (count($allIdsession) == 0) {
            return response()->json([
                "statu"=>false,
                "message"=>"Aucune sessions n'est programmé pour ce cours",
                "data"=>[]
            ]);
        }
        $allSession = [];

        // tous les sessions de cours
        if ($etat == "3") {
            for ($i=0; $i < count($allIdsession); $i++) { 
                $session = Session::where(['id'=>$allIdsession[$i]])->first();
                if ($session != null) {
                    array_push($allSession,new SessionResource($session));
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Tous les sessions",
                "data"=>SessionResource::collection($allSession)
            ]);
        }

        //sessions programmées
        if ($etat == "0") {
            for ($i=0; $i < count($allIdsession); $i++) { 
                $session = Session::where(['etat'=>"0",'id'=>$allIdsession[$i]])->first();
                if ($session != null) {
                    array_push($allSession,new SessionResource($session));
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Tous les sessions programmés",
                "data"=>SessionResource::collection($allSession)
            ]);
        }

        //sessions faites
        if ($etat == "1") {
            for ($i=0; $i < count($allIdsession); $i++) { 
                $session = Session::where(['etat'=>"1",'id'=>$allIdsession[$i]])->first();
                if ($session != null) {
                    array_push($allSession,new SessionResource($session));
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Tous les sessions faites",
                "data"=>SessionResource::collection($allSession)
            ]);
        }

        //sessions demande d'annulation
        if ($etat == "1") {
            for ($i=0; $i < count($allIdsession); $i++) { 
                $session = Session::where(['etat'=>"1",'id'=>$allIdsession[$i]])->first();
                if ($session != null) {
                    array_push($allSession,new SessionResource($session));
                }
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Tous les sessions qui sont en demande d'annulation",
                "data"=>SessionResource::collection($allSession)
            ]);
        }
    }

    public function annuler($id) {
        Session::where(['id'=>$id])->delete();
        Annulation::where(['id'=>$id])->delete();
        return response()->json([
            "statu"=>true,
            "message"=>"Session annulé",
            "data"=>[]
        ]);
    }

    public function validation($id) {
        Session::where(['id'=>$id])->update(['etat' => "1"]);
        return response()->json([
            "statu"=>true,
            "message"=>"Session validé",
            "data"=>[]
        ]);
    }

    public function byProfesseur($id,$etat) {
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
        // $classeAnnee_id = ClasseAnnee::where(['annee_scolaire_id'=>$an])->pluck('id');
        // if (count($classeAnnee_id) == 0) {
        //     return response()->json([
        //         "message"=>"Vous n'avez pas de cours cette année",
        //         "data"=>[]
        //     ]);
        // }
        // $COURS_ID = [];
        // for ($g=0; $g < count($classeAnnee_id); $g++) { 
        //     $p = ClasseAnneeCours::where(['classe_annee_id'=>$classeAnnee_id[$g]])->pluck('cours_id');
        //     if (count($p) != 0) {
        //         for ($q=0; $q < count($p); $q++) { 
        //             array_push($COURS_ID,$p[$q]);
        //         }
        //     }
        // }
        $tab = array_unique($allCours);
        $interCours = array_intersect($tab,$allCours);
        $session_id = [];
        for ($v=0; $v < count($allCours); $v++) { 
            if (array_key_exists($v,$interCours)) {
                $u = Session::where(['cours_id'=>$interCours[$v]])->get();
                if (count($u) != 0) {
                    for ($t=0; $t < count($u); $t++) { 
                        array_push($session_id,$u[$t]->id);
                    }
                }
            }
        }
        $sessionProf = [];
        if($etat == "1"){
            for ($i=0; $i < count($session_id); $i++) { 
                $c = Session::where(['etat'=>$etat,'id'=>$session_id[$i]])->first();
                if ($c != null) {
                    array_push($sessionProf,new SessionResource($c));
                }  
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tes session de cours terminé",
                "data"=>$sessionProf
            ]);
        }
        if($etat == "0"){
            for ($i=0; $i < count($session_id); $i++) { 
                $c = Session::where(['etat'=>$etat,'id'=>$session_id[$i]])->first();
                if ($c != null) {
                    array_push($sessionProf,new SessionResource($c));
                }  
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tes session de cours qui reste",
                "data"=>$sessionProf
            ]);
        }
        if($etat == "2"){
            for ($i=0; $i < count($session_id); $i++) { 
                $c = Session::where(['id'=>$session_id[$i]])->first();
                if ($c != null) {
                    array_push($sessionProf,new SessionResource($c));
                }  
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Voici tous tes session de cours",
                "data"=>$sessionProf
            ]);
        }
    }

    public function demandeAnnulation($id) {
        $annulation = new Annulation();
        $annulation->session_id = $id;
        $annulation->save();
        return response()->json([
            "statu"=>true,
            "message"=>"Demande envoyé"
        ]);
    }

    public function lesDemandesAnnulation() {
       $demande = Annulation::all();
       $tab = [];
        if (count($demande) != 0) {
            for ($i=0; $i < count($demande); $i++) { 
                array_push($tab,new SessionResource(Session::where(['id'=>$demande[$i]->session_id])->first()));
            }
            return response()->json([
                "statu"=>true,
                "message"=>"Les demandes D'annulation",
                "data"=>$tab
            ]);
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Les demandes D'annulation",
            "data"=>[]
        ]); 
    }

    public function sessionJour() {
        $date = getdate()["year"].'-'.getdate()["mon"].'-'.getdate()["mday"];
        $sessions = Session::where(['date'=>$date,'etat'=>"0"])->get();
        if (count($sessions) != 0) {
            return response()->json([
                "statu"=>true,
                "messages"=>"les sessions à valider",
                "data"=>SessionResource::collection($sessions)
            ]);
        }        
    }

    public function refus($id) {
        Annulation::where(['id'=>$id])->delete();
        return response()->json([
            "statu"=>true,
            "message"=>"Demande refusé",
            "data"=>[]
        ]);
    }


}




















