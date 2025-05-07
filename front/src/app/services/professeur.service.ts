import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { Reponse } from '../interfaces/reponse';
import { Professeur } from '../interfaces/professeur';
import { Cours } from '../interfaces/cours';
import { Module } from '../interfaces/module';
import { Session } from '../interfaces/session';
import Swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})
export class ProfesseurService {

  uri: string = environment.uri

  constructor(private http:HttpClient) { }
  Allprofs():Observable<Reponse<Professeur>>{
    return this.http.get<Reponse<Professeur>>(`${this.uri}/professeur`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  coursProfs(an:number,id:number,etat:number):Observable<Reponse<Cours>>{
    return this.http.get<Reponse<Cours>>(`${this.uri}/annee/${an}/cours/professeur/${id}/etat/${etat}`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  nbreHeure(id:number,module:number):Observable<Reponse<number>>{
    return this.http.get<Reponse<number>>(`${this.uri}/professeur/${id}/heure/${module}`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  moduleByProf(id:number):Observable<Reponse<Module>>{
    return this.http.get<Reponse<Module>>(`${this.uri}/professeur/${id}/module`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  sesionByProf(id:number,etat:number):Observable<Reponse<Session>>{
    return this.http.get<Reponse<Session>>(`${this.uri}/session/cours/professeur/${id}/etat/${etat}`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  public addProf(objet:object):Observable<Reponse<Professeur>>{
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post<Reponse<Professeur>>(`${this.uri}/professeur`,objet,{headers:header}).pipe(
      tap({
        next: (response) => {
          Swal.fire({
            title:'Success',
            icon:'success',
            text:response.message
          })
      }, error: (response) => {
          Swal.fire({
            title:'Error',
            icon:'error',
            text:response.message
          }) 
      }
        
      })
    )
  }

  /**
   * inscriptionEtudiants
   */
  public inscriptionEtudiants(objet:FormData) {
    return this.http.post(`${this.uri}/annee/1/inscription`,objet)
  }
}
