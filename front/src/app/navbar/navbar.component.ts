import { Component, OnInit } from '@angular/core';
import { Reponse } from '../interfaces/reponse';
import { Annee } from '../interfaces/annee';
import { CoursService } from '../services/cours.service';
import { UserLogin } from '../interfaces/user-login';
import { LoginService } from '../services/login.service';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit{
  allAnnee!: Reponse<Annee>
  AnneeSelected!:string
  userConnect!:UserLogin

  constructor(private coursService:CoursService,private loginService:LoginService){}
  ngOnInit(): void {
    this.coursService.annees().subscribe(response=>{
      this.allAnnee = response
      this.AnneeSelected = response.data[0].libelle
    })
    this.userConnect = JSON.parse(this.loginService.getUser()!);    
  }

  selectionAnnee(event:Event){
    let element = event.target as HTMLDivElement;
    this.coursService.updateEtat(+element.id).subscribe();
    let ele : Annee =this.allAnnee.data.find((el : Annee) => el.id === +element.id)!;
    this.allAnnee.data = this.allAnnee.data.filter((an : Annee) => an.id != +element.id)
    this.allAnnee.data.unshift(ele);
    this.AnneeSelected = this.allAnnee.data[0].libelle;
  }

  deconnexion(){
    this.loginService.disconnect()
  }
} 