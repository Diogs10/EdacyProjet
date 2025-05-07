import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { Reponse } from '../interfaces/reponse';
import { Classe } from '../interfaces/classe';
import Swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})
export class ClasseService {

  uri: string = environment.uri

  constructor(private http:HttpClient) { }
  /**
   * classes
   */
  public classes():Observable<Reponse<Classe>> {
    return this.http.get<Reponse<Classe>>(`${this.uri}/classe`).pipe(
      tap(response=>{
        // console.log(response);
      })
    )
  }

  public addClasse(objet:object):Observable<Reponse<Classe>>{
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post<Reponse<Classe>>(`${this.uri}/classe`,objet,{headers:header}).pipe(
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
}
