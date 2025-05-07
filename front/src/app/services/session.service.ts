import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { Reponse } from '../interfaces/reponse';
import { environment } from 'src/environments/environment.development';
import { Session } from '../interfaces/session';

@Injectable({
  providedIn: 'root'
})
export class SessionService {
  uri: string = environment.uri

  constructor(private http:HttpClient) { }
  /**
   * session
   */
  allsession():Observable<Reponse<Session>>{
    return this.http.get<Reponse<Session>>(`${this.uri}/session`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  demandeAnnulation(id:number) {
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post(`${this.uri}/annulation/${id}`,{headers:header})
  }

  lesDemandesAnnulation():Observable<Reponse<Session>> {
    return this.http.get<Reponse<Session>>(`${this.uri}/annulation`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  annuler(id:number):Observable<Reponse<Session>> {
    return this.http.delete<Reponse<Session>>(`${this.uri}/session/${id}`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  refus(id:number):Observable<Reponse<Session>> {
    return this.http.delete<Reponse<Session>>(`${this.uri}/session/${id}/refus`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  sessionAValider():Observable<Reponse<Session>>{
    return this.http.get<Reponse<Session>>(`${this.uri}/session/jour`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  validation(id:number){
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.put(`${this.uri}/session/${id}`,{headers:header}).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }
}
