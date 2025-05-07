import { Component } from '@angular/core';
import { Reponse } from '../interfaces/reponse';
import { Annee } from '../interfaces/annee';
import { AnneeService } from '../services/annee.service';
import { LoginService } from '../services/login.service';
import { UserLogin } from '../interfaces/user-login';

@Component({
  selector: 'app-attache-nav-bar',
  templateUrl: './attache-nav-bar.component.html',
  styleUrls: ['./attache-nav-bar.component.css']
})
export class AttacheNavBarComponent {
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
