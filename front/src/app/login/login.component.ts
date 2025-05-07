import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { LoginService } from '../services/login.service';
import { Login } from '../interfaces/login';
import { Router } from '@angular/router';
// import notification from 'sweetalert2';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent  implements OnInit{
  userConnecte!:Login
  connexion!:FormGroup
  constructor(private formBuilder:FormBuilder,private loginService:LoginService,private route:Router){
    this.connexion = this.formBuilder.group({
      email : ['',[Validators.required]],
      password : ['', Validators.required],
    });
  }
  ngOnInit(): void {
    if (this.loginService.getToken()) {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    }  
  }

  login(){
    this.loginService.login(this.connexion.value).subscribe(response=>{
      this.userConnecte = response;
      
      if ((this.userConnecte.statu == true)) {
        if (this.userConnecte.user.role_id == 2) {
          this.loginService.saveTokenUser(response.token,response.user);
          this.route.navigateByUrl('/prof/cours')
        }
        else if (this.userConnecte.user.role_id == 3) {
          this.loginService.saveTokenUser(response.token,response.user);
          this.route.navigateByUrl('/attache/session')
        }
        else if (this.userConnecte.user.role_id == 4) {
          this.loginService.saveTokenUser(response.token,response.user);
          this.route.navigateByUrl('/annee')
        }
        else{
          this.route.navigateByUrl('')
        }
      }
      else{
        this.route.navigateByUrl('');
      }
    });
  }
}
