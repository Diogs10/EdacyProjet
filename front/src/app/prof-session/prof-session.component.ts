import { Component, OnInit, ViewChild } from '@angular/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import { ProfesseurService } from '../services/professeur.service';
import { Reponse } from '../interfaces/reponse';
import { Session } from '../interfaces/session';
import { CalendarOptions } from '@fullcalendar/core';
import { UserLogin } from '../interfaces/user-login';
import { LoginService } from '../services/login.service';
import { ProfNavbarComponent } from '../prof-navbar/prof-navbar.component';
import { SessionService } from '../services/session.service';


@Component({
  selector: 'app-prof-session',
  templateUrl: './prof-session.component.html',
  styleUrls: ['./prof-session.component.css']
})
export class ProfSessionComponent implements OnInit {
  @ViewChild (ProfNavbarComponent) profnavbar! : ProfNavbarComponent
  allSessionProf!:Reponse<Session>
  userConnect!:UserLogin
  constructor(private profService:ProfesseurService,private loginService:LoginService,private sessionService:SessionService){}
  ngOnInit(): void {
    this.userConnect = JSON.parse(this.loginService.getUser()!);
    this.profService.sesionByProf(this.userConnect.id,2).subscribe(response=>{
      this.allSessionProf =response;
      let clanEvents = this.allSessionProf.data.map((ele:Session)=>{
        ele.date = new Date(ele.date)
        let heureDebut = new Date(Date.UTC(ele.date.getFullYear(), ele.date.getMonth(), ele.date.getDate(), +ele.heureDebut, 0, 0))
        let heuref = (+ele.heureDebut) + (+ele.duree)
        let heureFin = new Date(Date.UTC(ele.date.getFullYear(), ele.date.getMonth(), ele.date.getDate(), heuref, 0, 0))
        let title = `${ele.cours_id.module_prof_id.module}-${ele.cours_id.module_prof_id.professeur.prenom}-${ele.cours_id.semestre_id.libelle}-${ele.salle_id.nom}`
        return {id:`${ele.id}`,title:title,start:new Date(heureDebut),end:new Date(heureFin),color: '#ff9f89'};
      })  
      this.calendarOptions.events = clanEvents
    })
  }

  calendarOptions: CalendarOptions = {
    locale:"fr",
    plugins: [timeGridPlugin],
    initialView: 'timeGridWeek',
    initialDate:new Date(),
    weekends: true,
    headerToolbar: {
      left: 'prev,next',
      center: 'title',
      right: 'timeGridWeek,timeGridDay,timeGridMonth' // user can switch between the two
    },
    // eventClick: function(info) {
    //   var eventObj = info.event.id;
    //   if (window.confirm(`Voulez vous faire une demande d'annulation`)) {
        
    //   }
    // }
  }

  demande(){
    let annulation = document.querySelector('#annulation') as HTMLSelectElement;
    this.sessionService.demandeAnnulation(+annulation.value).subscribe()
  }
}
