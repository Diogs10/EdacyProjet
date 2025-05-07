<?php

use App\Http\Controllers\AnneeScolaireController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\SalleController;
use App\Http\Controllers\SemestreController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [UserController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);

//cours
Route::post('/cours',[CoursController::class,'register']);
Route::get('/annee/{id}/cours/etat/{etat}',[CoursController::class,'all']);
Route::get('/annee/{an}/cours/etudiant/{id}/etat/{etat}',[CoursController::class,'byEtudiant']);
Route::get('/annee/{an}/cours/professeur/{id}/etat/{etat}',[CoursController::class,'byProfesseur']);



//session
Route::post('/cours/{id}/session',[SessionController::class,'register']);
Route::get('/session',[SessionController::class,'all']);
Route::get('/cours/{id}/session/{etat}',[SessionController::class,'byCours']);
Route::put('/session/{id}',[SessionController::class,'validation']);
Route::delete('/session/{id}',[SessionController::class,'annuler']);
Route::delete('/session/{id}/refus',[SessionController::class,'refus']);
Route::get('/session/cours/professeur/{id}/etat/{etat}',[SessionController::class,'byProfesseur']);
Route::post('/annulation/{id}',[SessionController::class,'demandeAnnulation']);
Route::get('/annulation',[SessionController::class,'lesDemandesAnnulation']);
Route::get('/session/jour',[SessionController::class,'sessionJour']);




//module
Route::get('/module',[ModuleController::class,'all']);
Route::post('/module',[ModuleController::class,'register']);


//user
Route::get('/user',[UserController::class,'all']);
Route::get('/module/{id}/professeur',[UserController::class,'byModule']);
Route::post('/professeur',[UserController::class,'registerProf']);
Route::get('/professeur',[UserController::class,'allProf']);
Route::get('/professeur/{id}/heure/{module}',[UserController::class,'nbreHeureMois']);
Route::get('/professeur/{id}/module',[UserController::class,'modulebyProf']);



//etudiants
Route::post('/annee/{id}/inscription',[UserController::class,'import']);
Route::get('/cours/{id}/etudiants',[UserController::class,'etudiantsByCours']);



//classe
Route::get('/classe/{id}',[ClasseController::class,'all']);
Route::post('/classe',[ClasseController::class,'register']);
Route::get('/classe',[ClasseController::class,'allClasse']);
Route::post('/annee/{id}/classe',[ClasseController::class,'PlanificationClasse']);
Route::get('/annee/classe',[ClasseController::class,'allClasseAnnee']);
Route::get('/annee/{id}/noplane',[ClasseController::class,'nonPlanifier']);




//annee
Route::get('/annee',[AnneeScolaireController::class,'all']);
Route::post('/annee',[AnneeScolaireController::class,'register']);
Route::put('/annee/{id}',[AnneeScolaireController::class,'updateEtat']);



//semestre
Route::get('/semestre',[SemestreController::class,'all']);
Route::post('/semestre',[SemestreController::class,'register']);



//salle
Route::post('/salle',[SalleController::class,'register']);
Route::get('/salle',[SalleController::class,'all']);
Route::put('/salle/{id}',[SalleController::class,'update']);
Route::delete('/salle/{id}',[SalleController::class,'delete']);


});
