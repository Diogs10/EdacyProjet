<?php

namespace App\Imports;

use App\Mail\SendEmails;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\ClasseAnnee;
use App\Models\Inscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return DB::transaction(function ()use ($row) {
            $allEmail = User::all();
            $user = new User();
            if (count($allEmail) == 0) {
                $user->nom = $row['nom'];
                $user->prenom = $row['prenom'];
                $user->email = $row['email'];
                $user->telephone = $row['telephone'];
                $user->photo = $row['photo'];
                $user->password =  Hash::make($row['password']);
                $user->date_naissance = $row['date_naissance'];
                $user->lieu_naissance = $row['lieu_naissance'];
                $user->role_id = 1;
                $user->save();
                // Mail::to($user->email)->send(new SendEmails($user->prenom));

            }
            else {
                $a = 0;
                for ($i=0; $i < count($allEmail); $i++) {
                    if ($row['email'] != $allEmail[$i]->email) {
                        $a = $a + 1;
                    }
                }
                if ($a == count($allEmail)) {
                    $user->nom = $row['nom'];
                    $user->prenom = $row['prenom'];
                    $user->email = $row['email'];
                    $user->telephone = $row['telephone'];
                    $user->photo = $row['photo'];
                    $user->password =  Hash::make($row['password']);
                    $user->date_naissance = $row['date_naissance'];
                    $user->lieu_naissance = $row['lieu_naissance'];
                    $user->role_id = 1;
                    $user->save();
                    // Mail::to($user->email)->send(new SendEmails($user->prenom));

                }
                else {
                    $user = User::where(['email'=>$row['email']])->first();
                }
            }
            $classe = Classe::where(['filiere'=>$row['filiere'],'niveau'=>$row['niveau']])->first();
            $an = AnneeScolaire::where(['etat'=>'1'])->first();
            $classeAnn = 0;
            if (($classe != null) && ($an != null)) {
                $classeAnn = ClasseAnnee::where(['classe_id'=>$classe->id,'annee_scolaire_id'=>$an->id])->first();
                $classeAnn->effectif = $classeAnn->effectif + 1;
                ClasseAnnee::where(['id'=>$classeAnn->id])->update(['effectif'=>$classeAnn->effectif]);
            }
            if ($classeAnn != null) {
                $inscription = new Inscription();
                $inscription->user_id = $user->id;
                $inscription->classe_annee_id = $classeAnn->id;
                $inscription->save();
            }

            return $user;
        });
    }
}
