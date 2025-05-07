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
    console.log(this.addSalle.value);
  }

}
