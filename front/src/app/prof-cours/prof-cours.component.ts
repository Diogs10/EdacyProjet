import { Component, OnInit, ViewChild } from '@angular/core';
import { ProfesseurService } from '../services/professeur.service';
import { Reponse } from '../interfaces/reponse';
import { Cours } from '../interfaces/cours';
import { ProfNavbarComponent } from '../prof-navbar/prof-navbar.component';
import { Module } from '../interfaces/module';
import { UserLogin } from '../interfaces/user-login';
import { LoginService } from '../services/login.service';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-prof-cours',
  templateUrl: './prof-cours.component.html',
  styleUrls: ['./prof-cours.component.css']
})
export class ProfCoursComponent implements OnInit{
  @ViewChild (ProfNavbarComponent) profnavbar! : ProfNavbarComponent
  coursProfs!:Reponse<Cours>
  moduleByProf!:Reponse<Module>
  nbreHeure!:number
  userConnect!:UserLogin
  constructor(private profService: ProfesseurService,private loginService:LoginService){}
  ngOnInit(): void {
    this.userConnect = JSON.parse(this.loginService.getUser()!);
    this.profService.coursProfs(1,this.userConnect.id,2).subscribe(response=>{
      this.coursProfs = response
      if (this.coursProfs.statu == true) {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
      }
      else{
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
      }
    });
    this.profService.moduleByProf(this.userConnect.id).subscribe(response=>{
      this.moduleByProf = response
    })
    this.profService.nbreHeure(this.userConnect.id,0).subscribe(response=>{
      this.nbreHeure = response.data[0]
    })
  }

  public filtreCours(event : Event) {
    const select = event.target as HTMLSelectElement;
    this.profService.coursProfs(this.profnavbar.allAnnee.data[0].id,this.userConnect.id,+select.value).subscribe(response=>{
      this.coursProfs = response
      if (this.coursProfs.statu == true) {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
      }
    });
  }

  profModule(event:Event){
    const select = event.target as HTMLSelectElement;
    this.profService.nbreHeure(this.userConnect.id,+select.value).subscribe(response=>{
      this.nbreHeure = response.data[0]
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
    })   
  }

}
