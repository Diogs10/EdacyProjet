import { Component, OnInit } from '@angular/core';
import { SessionService } from '../services/session.service';
import { Reponse } from '../interfaces/reponse';
import { Session } from '../interfaces/session';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-attache-demande-annulation',
  templateUrl: './attache-demande-annulation.component.html',
  styleUrls: ['./attache-demande-annulation.component.css']
})
export class AttacheDemandeAnnulationComponent implements OnInit{
  lesDemandes!:Reponse<Session>
  constructor(private sessionService:SessionService){}
  ngOnInit(): void {
    this.sessionService.lesDemandesAnnulation().subscribe(response=>{
      this.lesDemandes = response
    })
  }

  annule(event:Event){
    let ee = event.target as HTMLButtonElement;
    this.sessionService.annuler(+ee.id).subscribe(response=>{
      let index = this.lesDemandes.data.findIndex(function(element:Session) {
        return element.id === +ee.id;
      });
      this.lesDemandes.data.splice(index,1);
      Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: response.message,
        showConfirmButton: false,
        timer: 1500
      })
    })

  }

  refus(event:Event){
    let ee = event.target as HTMLButtonElement;
    this.sessionService.refus(+ee.id).subscribe(response=>{
      let index = this.lesDemandes.data.findIndex(function(element:Session) {
        return element.id === +ee.id;
      });
      this.lesDemandes.data.splice(index,1);
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
