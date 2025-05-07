<?php

namespace App\Http\Controllers;

use App\Mail\SendEmails;
use App\Models\Absence;
use App\Models\Inscription;
use App\Models\Notification;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AbsenceController extends Controller
{
    //
    public function register(Request $request){
        for ($i=0; $i < $request->absences; $i++) {
            $absence = new Absence();
            $absence->inscription_id = $request->absences[$i];
            $absence->session_id = $request->session_id;
            $absence->etat = "0";
            $absence->date = now();
            $absence->save();
            $allAbsence = Absence::where(['inscription_id'=>$request->absences[$i],'etat'=>"0"])->pluck('session_id');
            $nbreHeureAbsent = 0;
            $ins = Inscription::where(['id'=>$request->absences[$i]])->first();
            if ($ins != null) {
                $user = User::where(['id'=>$ins->user_id])->first();
            }
            for ($j=0; $j < count($allAbsence); $j++) {
                $nbreHeureAbsent += Session::where(['id'=>$allAbsence[$j]])->first()->duree;
            }
            if ($nbreHeureAbsent >= 10 && $nbreHeureAbsent < 20 ) {
                $notification = Notification::where(['inscription_id'=>$request->absences[$i]])->get();
                if (count($notification) == 0) {
                    // Mail::to($user->email)->send(new SendEmails($user->prenom));
                    echo 'envoyé mail avertissement';
                    $notif =new Notification();
                    $notif->inscription_id = $request->absences[$i];
                    $notif->description = 'Avertissement';
                    $notif->date = now();
                    $notif->save();
                }
            }
            if ($nbreHeureAbsent >= 20) {
                $notif =new Notification();
                $notif->inscription_id = $request->absences[$i];
                $notif->description = 'Avertissement';
                $notif->date = now();
                $notif->save();
                // Mail::to($user->email)->send(new SendEmails($user->prenom));
                echo 'envoyé mail convocation';
            }
        }
        return;
    }

    public function demandeJustification(Request $request){
        Absence::where(['id'=> $request->id])->update(['motif' => $request->motif]);
        return response()->json([
            "statu"=>true,
            "message"=>"Justification envoyée"
        ]);
    }

    public function AcceptationJustification(Request $request){
        Absence::where(['id'=> $request->id])->update(['etat' => $request->etat]);
        if ($request->etat == "0" || $request->etat == 0) {
            return response()->json([
                "statu"=>true,
                "message"=>"Absence non justifiée"
            ]);
        }
        return response()->json([
            "statu"=>true,
            "message"=>"Absence justifiée"
        ]);
    }
}
