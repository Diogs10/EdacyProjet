import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { Reponse } from '../interfaces/reponse';
import { Annee } from '../interfaces/annee';
import { Classe } from '../interfaces/classe';

@Injectable({
  providedIn: 'root'
})
export class AnneeService {
  uri: string = environment.uri

  constructor(private http:HttpClient) { }
  annees():Observable<Reponse<Annee>>{
    return this.http.get<Reponse<Annee>>(`${this.uri}/annee`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  classesNoPlanifier(id:number):Observable<Reponse<Classe>>{
    return this.http.get<Reponse<Classe>>(`${this.uri}/annee/${id}/noplane`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  addAnnee(objet:object):Observable<Reponse<Annee>>{
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post<Reponse<Annee>>(`${this.uri}/annee`,objet,{headers:header}).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }
  planeClasse(id:number,objet:object){
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post(`${this.uri}/annee/${id}/classe`,objet,{headers:header}).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  
}
