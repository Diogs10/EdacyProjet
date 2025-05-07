import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { Reponse } from '../interfaces/reponse';
import { Salle } from '../interfaces/salle';
import Swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})
export class SalleService {

  uri: string = environment.uri

  constructor(private http:HttpClient) { }
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

  public addSalle(objet:object):Observable<Reponse<Salle>>{
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post<Reponse<Salle>>(`${this.uri}/salle`,objet,{headers:header}).pipe(
      tap({
        next: (response) => {
          // Swal.fire({
          //   title:'Success',
          //   icon:'success',
          //   text:response.message
          // })  
      }, error: (response) => {
          // Swal.fire({
          //   title:'Error',
          //   icon:'error',
          //   text:response.message
          // }) 
      }
        
      })
    )
  }
}
