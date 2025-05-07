import { Component, OnInit } from '@angular/core';
import { SalleService } from '../services/salle.service';
import { Reponse } from '../interfaces/reponse';
import { Salle } from '../interfaces/salle';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-salle',
  templateUrl: './salle.component.html',
  styleUrls: ['./salle.component.css']
})
export class SalleComponent implements OnInit{
  allSalles!:Reponse<Salle>
  addSalle!:FormGroup
  p: number = 1;
  isEdit:boolean = false;
  constructor(private salleService:SalleService,private formbuilder:FormBuilder){
    this.addSalle = this.formbuilder.group({
      id:[''],
      nom:['',[Validators.required]],
      effectif:['',[Validators.required]],
    })
  }
  ngOnInit(): void {
    this.salleService.salles().subscribe(response=>{
      this.allSalles = response
    })
  }

  addorUpdateSalle(){
    if (this.isEdit) {
      this.updateSalle()
    } else {
      this.ajoutSallle()
    }
  }

  ajoutSallle(){
    this.salleService.addSalle(this.addSalle.value).subscribe(response=>{
      if (response.statu == true) {
        Swal.fire({
          title:'Success',
          icon:'success',
          text:response.message
        })
        this.allSalles.data.unshift(response.data[0])
        this.addSalle.reset()
      } else {
        Swal.fire({
          title:'Error',
          icon:'error',
          text:response.message
        })
      }
    })
  }

  deleteSalle(id:number){
    Swal.fire({
      title:'Are you sure?',
      text:'You won\'t be able to revert this!',
      icon:'warning',
      showCancelButton:true,
      confirmButtonColor:'#3085d6',
      cancelButtonColor:'#d33',
      confirmButtonText:'Yes, delete it!'
    }).then((result)=>{
      if (result.isConfirmed) {
        this.salleService.deleteSalle(id).subscribe(response=>{
          if (response.statu == true) {
            Swal.fire({
              title:'Deleted!',
              text:response.message,
              icon:'success'
            })
            this.allSalles.data = this.allSalles.data.filter((salle)=>salle.id != id)
          } else {
            Swal.fire({
              title:'Error!',
              text:response.message,
              icon:'error'
            })
          }
        })
      }
    })
  }

  editSalle(salle:Salle){
    this.isEdit = true;
    this.addSalle.patchValue({
      id:salle.id,
      nom:salle.nom,
      effectif:salle.effectif
    })
  }

  updateSalle(){
    this.salleService.updateSalle(this.addSalle.value.id,this.addSalle.value).subscribe(response=>{
      if (response.statu == true) {
        Swal.fire({
          title:'Success',
          icon:'success',
          text:response.message
        })
        this.salleService.salles().subscribe(response=>{
          this.allSalles = response
        })
        this.addSalle.reset()
        this.isEdit = false;
      } else {
        Swal.fire({
          title:'Error',
          icon:'error',
          text:response.message
        })
      }
    })
  }

}
