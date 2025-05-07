import { Component, OnInit, ViewChild } from '@angular/core';
import { CalendarOptions } from '@fullcalendar/core';
import timeGridPlugin from '@fullcalendar/timegrid';
import { SessionService } from '../services/session.service';
import { Reponse } from '../interfaces/reponse';
import { Session } from '../interfaces/session';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-session',
  templateUrl: './session.component.html',
  styleUrls: ['./session.component.css']
})
export class SessionComponent implements OnInit {
  
  session!:Reponse<Session>
  constructor(private sessionService:SessionService){}
  ngOnInit(): void {
    this.sessionService.allsession().subscribe(response=>{
      this.session =response;
      if (this.session.statu == true) {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
      }
      let clanEvents = this.session.data.map((ele:Session)=>{
        ele.date = new Date(ele.date)
        let heureDebut = new Date(Date.UTC(ele.date.getFullYear(), ele.date.getMonth(), ele.date.getDate(), +ele.heureDebut, 0, 0))
        let heuref = (+ele.heureDebut) + (+ele.duree)
        let heureFin = new Date(Date.UTC(ele.date.getFullYear(), ele.date.getMonth(), ele.date.getDate(), heuref, 0, 0))
        let title = `${ele.cours_id.module_prof_id.module}-${ele.cours_id.module_prof_id.professeur.prenom}-${ele.cours_id.semestre_id.libelle}-${ele.salle_id.nom}`
        let co:string = ''
        if (ele.etat == '1') {
          co = '#ff0000'
        } else {
          co = '#00ff00 '
        }
        return {title:title,start:new Date(heureDebut),end:new Date(heureFin),color: co};
      })  
      this.calendarOptions.events = clanEvents
      console.log(clanEvents);
      
    })
  }
  calendarOptions: CalendarOptions = {
    locale:"fr",
    plugins: [timeGridPlugin],
    initialView: 'timeGridWeek',
    initialDate:new Date(),
    weekends: true,
    // events: [
    //   { title: 'Meeting', start: new Date('2023-10-11 08:06:25'), end: new Date('2023-10-11 10:06:25'),color: '#ff9f89'},
    //   { title: 'Meeting', start: new Date('2023-10-11 09:06:25'), end: new Date('2023-10-11 11:06:25'),color: '#531CB3'},
    //   { title: 'Meeting', start: new Date('2023-10-11 11:06:25'), end: new Date('2023-10-11 13:06:25'),color: '#ff9f89'},
    //   { title: 'Meeting', start: new Date('2023-10-11 14:06:25'), end: new Date('2023-10-11 16:06:25'),color: '#ff9f89'},
    //   { title: 'Meeting', start: new Date('2023-10-11 17:06:25'), end: new Date('2023-10-11 19:06:25'),color: '#ff9f89'}
    // ],
    headerToolbar: {
      left: 'prev,next',
      center: 'title',
      right: 'timeGridWeek,timeGridDay,timeGridMonth' // user can switch between the two
    }
  };

}
