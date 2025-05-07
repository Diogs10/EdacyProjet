import { Component, OnInit } from '@angular/core';
import { ProfesseurService } from '../services/professeur.service';
import { Reponse } from '../interfaces/reponse';
import { Professeur } from '../interfaces/professeur';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ModuleService } from '../services/module.service';
import { Module } from '../interfaces/module';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.css']
})
export class UserComponent implements OnInit {
  fil!:File
  p: number = 1;
  pp: number = 1;
  AllProfs !:Reponse<Professeur>
  AllModules !:Reponse<Module>
  moduleAjoute !:Module
  addProf!:FormGroup
  modules:number[] = []
  isCharge:boolean = false

  constructor(private profService:ProfesseurService,private formBuilder:FormBuilder,private moduleService:ModuleService){
    this.addProf = this.formBuilder.group({
      id: [''],
      nom: ['',[ Validators.required]],
      prenom : ['',[Validators.required]],
      telephone : ['',[Validators.required]],
      email : ['', Validators.required],
      specialite : ['', Validators.required],
      grade : ['', Validators.required],
    })
  }
  ngOnInit(): void {
    this.profService.Allprofs().subscribe(response=>{
      this.AllProfs = response;
    });
    this.moduleService.modules().subscribe(response=>{
      this.AllModules = response
    })
  }

  addModule(){
    let module = document.querySelector('#module') as HTMLInputElement;
    let objet : object = {
      'libelle':module.value
    }
    this.moduleService.addModule(objet).subscribe(response=>{
      if (response.statu) {
        this.moduleAjoute = response.data[0];
        this.AllModules.data.unshift(this.moduleAjoute)
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
    });
  }

  ajoutProf(){
    this.modules = [];
    let cheks = document.querySelectorAll('.chek');
    cheks.forEach(element => {
      let e = element as HTMLInputElement
      if (e.checked) {
        this.modules.push(+e.id);
      }
    });
    let objet : object = {
      'nom':this.addProf.value.nom,
      'prenom':this.addProf.value.prenom,
      'telephone':this.addProf.value.telephone,
      'email':this.addProf.value.email,
      'specialite':this.addProf.value.specialite,
      'grade':this.addProf.value.grade,
      'modules':this.modules
    }
    console.log(objet);
    this.profService.addProf(objet).subscribe(response=>{
      if (response.statu == true) {
        this.AllProfs.data.unshift(response.data[0])
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
    this.addProf.reset();
    cheks.forEach(element => {
      let e = element as HTMLInputElement
      if (e.checked) {
        e.checked = false;
      }
    });
  }

  fichier(event : Event){
    const filesTarget = event.target as HTMLInputElement;
    if (filesTarget.files) {
      this.isCharge = true
      this.fil = filesTarget.files[0];
    }
  }

  inscription(){
    const files = new FormData
    files.append('file',this.fil)
    this.profService.inscriptionEtudiants(files).subscribe({
      next:(response)=>{
        Swal.fire({
          title:'Success',
          icon:'success',
          text:'Insertion reussi'
        })
        console.log(response);

      },
      error:(err)=>{
        Swal.fire({
          title:'Error',
          icon:'error',
          text:err
        })
      }
    })
  }

}
