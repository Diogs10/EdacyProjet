import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { Reponse } from '../interfaces/reponse';
import { Classe } from '../interfaces/classe';
import { Annee } from '../interfaces/annee';
import { Semestre } from '../interfaces/semestre';
import { Module } from '../interfaces/module';
import { Professeur } from '../interfaces/professeur';
import { Cours } from '../interfaces/cours';
import { ClasseAnnee } from '../interfaces/classe-annee';
import { Salle } from '../interfaces/salle';
import { Session } from '../interfaces/session';

@Injectable({
  providedIn: 'root'
})
export class CoursService {
  uri: string = environment.uri

  constructor(private http:HttpClient) { }
  classes(id:number):Observable<Reponse<Classe>>{
    return this.http.get<Reponse<Classe>>(`${this.uri}/classe/${id}`).pipe(
      tap(response=>{
        // console.log(response);
      })
    )
  }

  annees():Observable<Reponse<Annee>>{
    return this.http.get<Reponse<Annee>>(`${this.uri}/annee`).pipe(
      tap(response=>{
        // console.log(response);
      })
    )
  }

  semestres():Observable<Reponse<Semestre>>{
    return this.http.get<Reponse<Semestre>>(`${this.uri}/semestre`).pipe(
      tap(response=>{
        // console.log(response);
      })
    )
  }

  modules():Observable<Reponse<Module>>{
    return this.http.get<Reponse<Module>>(`${this.uri}/module`).pipe(
      tap(response=>{
        // console.log(response);
      })
    )
  }

  professeurs(id:number):Observable<Reponse<Professeur>>{
    return this.http.get<Reponse<Professeur>>(`${this.uri}/module/${id}/professeur`).pipe(
      tap(response=>{
        // console.log(response);
      })
    )
  }

  cours(id:number,etat:number):Observable<Reponse<Cours>>{
    return this.http.get<Reponse<Cours>>(`${this.uri}/annee/${id}/cours/etat/${etat}`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  public ajoutCours(objet:object):Observable<Reponse<Cours>> {
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post<Reponse<Cours>>(`${this.uri}/cours`,objet,{headers:header})
  }

  /**
   * allAnneeClasse
   */
  public allAnneeClasse():Observable<Reponse<ClasseAnnee>> {
    return this.http.get<Reponse<ClasseAnnee>>(`${this.uri}/annee/classe`).pipe(
      tap(response=>{
        // console.log(response);
      })
    )
  }

  /**
   * salles
   */
  public salles():Observable<Reponse<Salle>> {
    return this.http.get<Reponse<Salle>>(`${this.uri}/salle`).pipe(
      tap(response=>{
        // console.log(response);
      })
    )
  }

  /**
   * ajoutSession
   */
  ajoutSession(objet:object,id:number):Observable<Reponse<Session>> {
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post<Reponse<Session>>(`${this.uri}/cours/${id}/session`,objet,{headers:header})
  }


  /**
   * etudiantsByCours
   */
  public etudiantsByCours(id:number) {
    return this.http.get(`${this.uri}/cours/${id}/etudiants`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  public updateEtat(id:number){
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.put(`${this.uri}/annee/${id}`,{headers:header})
  }
}
