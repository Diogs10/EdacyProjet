import { Component, OnInit } from '@angular/core';
import { SessionService } from '../services/session.service';
import { Reponse } from '../interfaces/reponse';
import { Session } from '../interfaces/session';
import { Router } from '@angular/router';

@Component({
  selector: 'app-attache-session',
  templateUrl: './attache-session.component.html',
  styleUrls: ['./attache-session.component.css']
})
export class AttacheSessionComponent implements OnInit{
  sessionavalide!:Reponse<Session>
  constructor(private sessionService:SessionService,private route:Router){}
  ngOnInit(): void {
    this.sessionService.sessionAValider().subscribe(response=>{
      this.sessionavalide = response;
    })
  }

  valide(event :Event){
    let r = event.target as HTMLButtonElement
    this.sessionService.validation(+r.id).subscribe()
  }

  invalide(event:Event){
    let ee = event.target as HTMLButtonElement;
    this.sessionService.annuler(+ee.id).subscribe(response=>{
      console.log(response);
      if (response.statu == true) {
        
      }
    })
  }

  absence(event :Event){
    let r = event.target as HTMLButtonElement
    localStorage.setItem('session_cours',r.id)
    this.route.navigateByUrl('/absence')
  }
}
