import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, catchError, tap } from 'rxjs';
import { environment } from 'src/environments/environment.development';
import { Login } from '../interfaces/login';
import Swal from 'sweetalert2';
import { UserLogin } from '../interfaces/user-login';


@Injectable({
  providedIn: 'root'
})
export class LoginService {

  uri: string = environment.uri

  constructor(private http:HttpClient) { }
  login(objet:object):Observable<Login>{
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post<Login>(`${this.uri}/login`,objet,{headers:header}).pipe(
      tap({
        next: (response) => {
          Swal.fire({
            title:'Success',
            icon:'success',
            text:'Connexion réussie'
          })  
      }, error: (err) => {
          console.error(err);
          Swal.fire({
            title:'Error',
            icon:'error',
            text:'Email ou Mot de passe Incorrectes'
          }) 
      }
        
      })
    )
  }

  getToken(){
    return localStorage.getItem('token');
  }

  identication(){
    if (this.getToken()) {
      return true;
    }
    return false;
  }

  getUser(){
    return localStorage.getItem('user')
  }

  saveTokenUser(token:string,user:UserLogin){
    localStorage.setItem('token',token);
    localStorage.setItem('user',JSON.stringify(user));
  }

  disconnect(){
    const header = new HttpHeaders({'Content-Type': 'application/json'});
    return this.http.post(`${this.uri}/logout`,{headers:header}).pipe(
      tap(response=>{
        console.log(response);
      }))
  }
}
