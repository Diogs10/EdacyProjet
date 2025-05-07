import { Component, OnInit } from '@angular/core';
import { AbsenceService } from '../services/absence.service';
import { CoursService } from '../services/cours.service';

@Component({
  selector: 'app-absence',
  templateUrl: './absence.component.html',
  styleUrls: ['./absence.component.css']
})
export class AbsenceComponent implements OnInit{
  etudiants:any
  constructor(private absenceService:AbsenceService,private coursService:CoursService){}
  ngOnInit(): void {
    let a = localStorage.getItem('session_cours');
    let ref = a?.split('-');
    if (ref != undefined) {
      this.coursService.etudiantsByCours(+ref[1]).subscribe(response=>{
        this.etudiants = response
      })
    }
  }

}
