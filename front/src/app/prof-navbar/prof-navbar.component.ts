import { Component, OnInit } from '@angular/core';
import { AnneeService } from '../services/annee.service';
import { Annee } from '../interfaces/annee';
import { Reponse } from '../interfaces/reponse';
import { UserLogin } from '../interfaces/user-login';
import { LoginService } from '../services/login.service';

@Component({
  selector: 'app-prof-navbar',
  templateUrl: './prof-navbar.component.html',
  styleUrls: ['./prof-navbar.component.css']
})
export class ProfNavbarComponent implements OnInit{
  allAnnee!: Reponse<Annee>
  AnneeSelected!:string
  userConnect!:UserLogin

  constructor(private anneeService:AnneeService,private loginService:LoginService){}
  ngOnInit(): void {
    this.anneeService.annees().subscribe(response=>{
      this.allAnnee = response
      this.AnneeSelected = response.data[0].libelle
    })
    this.userConnect = JSON.parse(this.loginService.getUser()!);
  }

  selectionAnnee(event:Event){
    let element = event.target as HTMLDivElement;
    let ele : Annee =this.allAnnee.data.find((el : Annee) => el.id === +element.id)!;
    this.allAnnee.data = this.allAnnee.data.filter((an : Annee) => an.id != +element.id)
    this.allAnnee.data.unshift(ele);
    this.AnneeSelected = this.allAnnee.data[0].libelle;
  }
}
