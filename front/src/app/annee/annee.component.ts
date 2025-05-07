import { Component } from '@angular/core';
import { Reponse } from '../interfaces/reponse';
import { Annee } from '../interfaces/annee';
import { AnneeService } from '../services/annee.service';
import { Classe } from '../interfaces/classe';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-annee',
  templateUrl: './annee.component.html',
  styleUrls: ['./annee.component.css']
})
export class AnneeComponent {
  classes:number[] = []
  anneess!: Reponse<Annee>
  classePlane !:Reponse<Classe>
  anneeAjoute !:Annee
  p: number = 1;
  constructor(private anneeService:AnneeService){}

  ngOnInit(): void {
    this.anneeService.annees().subscribe(response=>{
      this.anneess = response;
    })
    this.anneeService.classesNoPlanifier(0).subscribe(response=>{
      this.classePlane = response;
      if (this.classePlane.statu == true) {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
      }
    })
  }
  
  chargerClassePlane(event:Event){
    let ele = event.target as HTMLButtonElement;
    this.anneeService.classesNoPlanifier(+ele.value).subscribe(response=>{
      this.classePlane = response;
    })
  }

  ajoutAnnee(){
    let annee = document.querySelector('#annee') as HTMLInputElement;
    let objet : object ={
      'libelle':annee.value
    }    
    this.anneeService.addAnnee(objet).subscribe(response=>{
      if (response.statu == true) {
        this.anneeAjoute = response.data[0]
        this.anneess.data.unshift(this.anneeAjoute);
        annee.value = ""
        Swal.fire({
          title:'Success',
          icon:'success',
          text:response.message
        })
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

  planificationClasse(){
    this.classes = []
    let selection = document.querySelector('#selection') as HTMLSelectElement;
    let allClasses = document.querySelectorAll('.clas')
    allClasses.forEach(element => {
      let e = element as HTMLInputElement
      if (e.checked) {
        this.classes.push(+e.id);
      }
    });
    let objet : object ={
      'classes':this.classes
    }
    this.anneeService.planeClasse(+selection.value,objet).subscribe()
    this.anneeService.classesNoPlanifier(+selection.value).subscribe(response=>{
      this.classePlane = response;
      if (this.classePlane.statu == true) {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
      }
    })
  }

}
