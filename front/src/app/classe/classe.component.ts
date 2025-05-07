import { Component, OnInit } from '@angular/core';
import { ClasseService } from '../services/classe.service';
import { Reponse } from '../interfaces/reponse';
import { Classe } from '../interfaces/classe';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-classe',
  templateUrl: './classe.component.html',
  styleUrls: ['./classe.component.css']
})
export class ClasseComponent implements OnInit{
  allClasses !:Reponse<Classe>
  classeAjoute !:Reponse<Classe>
  addClasse!:FormGroup
  p: number = 1;
  constructor(private classeService:ClasseService,private formBuilder:FormBuilder){
    this.addClasse = this.formBuilder.group({
      filiere : ['',[Validators.required]],
      niveau : ['', Validators.required],
    })
  }
  ngOnInit(): void {
    this.classeService.classes().subscribe(response=>{
      this.allClasses = response;
    });
  }

  ajoutClasse(){
    this.classeService.addClasse(this.addClasse.value).subscribe(response=>{
      this.classeAjoute = response;
      if (this.classeAjoute.statu == true) {
        this.allClasses.data.unshift(this.classeAjoute.data[0]);
        Swal.fire({
          title:'Success',
          icon:'success',
          text:response.message
        })
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
