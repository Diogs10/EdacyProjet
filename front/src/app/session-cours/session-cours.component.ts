import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { CoursService } from '../services/cours.service';
import { Reponse } from '../interfaces/reponse';
import { Salle } from '../interfaces/salle';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-session-cours',
  templateUrl: './session-cours.component.html',
  styleUrls: ['./session-cours.component.css']
})
export class SessionCoursComponent implements OnInit{
  allSalle!:Reponse<Salle>
  presentiel :boolean = true
  addSession!:FormGroup
  etudiants: any;
  id !: string | null
  constructor(private formBuilder:FormBuilder,private coursService:CoursService){
    this.addSession = this.formBuilder.group({
      id: [''],
      heureDebut: ['',[ Validators.required]],
      duree : ['',[Validators.required]],
      date : ['',[Validators.required]],
      salle_id : ['', Validators.required],
      type:[false]
    });
  }
  ngOnInit(): void {
    this.id = localStorage.getItem('cours_id');
    this.coursService.salles().subscribe(response=>{
      this.allSalle = response
    })
    if (this.id != null) {
      this.coursService.etudiantsByCours(+this.id).subscribe(response=>{
        this.etudiants = response
      })
    }
  }

  get heureDebut() {
    return this.addSession.get("heureDebut");
  }

  get duree() {
    return this.addSession.get("duree");
  }

  get date() {
    return this.addSession.get("date");
  }

  get salle_id() {
    return this.addSession.get("salle_id");
  }

  get type() {
    return this.addSession.get("type");
  }

  ajoutSession() {
    if (this.id != null) {
      this.addSession.value.salle_id = +this.addSession.value.salle_id;
      this.coursService.ajoutSession(this.addSession.value,+this.id).subscribe(response=>{
        console.log(response);
        if (response.statu) {
          Swal.fire({
            title:'Success',
            icon:'success',
            text:response.message
          })  
          this.addSession.reset();
        }
        else{
          Swal.fire({
            title:'Error',
            icon:'error',
            text:response.message
          }) 
        }
      })
    }
    
  }

  presenceChecked(){
    let presence = document.getElementById("type") as HTMLInputElement;
    if (presence.checked) {
      this.presentiel = false
      this.salle_id?.setValue('');
    }
    else{
      this.presentiel = true;
    }
  }

  // detailCours(event : Event){
  //   const idCours = event.target as HTMLButtonElement;
  //   this.coursService.etudiantsByCours(+idCours.id).subscribe(response=>{
  //     this.etudiants = response
  //   })    
  // }
}
