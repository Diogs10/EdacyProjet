import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, tap } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { Reponse } from '../interfaces/reponse';
import { Module } from '../interfaces/module';
import Swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})
export class ModuleService {

  uri: string = environment.uri

  constructor(private http:HttpClient) { }
  /**
   * salles
   */
  public modules():Observable<Reponse<Module>> {
    return this.http.get<Reponse<Module>>(`${this.uri}/module`).pipe(
      tap(response=>{
        console.log(response);
      })
    )
  }

  public addModule(objet:object):Observable<Reponse<Module>>{
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post<Reponse<Module>>(`${this.uri}/module`,objet,{headers:header}).pipe(
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
