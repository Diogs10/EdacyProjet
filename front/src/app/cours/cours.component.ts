import { Component, OnInit, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { CoursService } from '../services/cours.service';
import { NavbarComponent } from '../navbar/navbar.component';
import { Cours } from '../interfaces/cours';
import { Reponse } from '../interfaces/reponse';
import { Semestre } from '../interfaces/semestre';
import { Module } from '../interfaces/module';
import { Professeur } from '../interfaces/professeur';
import { Classe } from '../interfaces/classe';
import { ClasseAnnee } from '../interfaces/classe-annee';
import { Salle } from '../interfaces/salle';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-cours',
  templateUrl: './cours.component.html',
  styleUrls: ['./cours.component.css']
})
export class CoursComponent implements OnInit{
/*______________________________________________________déclaration_______________________________________________________________*/  

  @ViewChild (NavbarComponent) navbar! : NavbarComponent
  p:number = 1
  fil!:File
  presentiel :boolean = true
  addCours!:FormGroup
  addSession!:FormGroup
  allCours!:Reponse<Cours>
  allSemestre!:Reponse<Semestre>
  allSalle!:Reponse<Salle>
  allModule!:Reponse<Module>
  allProf:Reponse<Professeur> = {"statu":false,"message":"","data":[]}
  allClasse:Reponse<Classe> = {"statu":false,"message":"","data":[]}
  classes:number[] = []
  allClasseAnnee!: Reponse<ClasseAnnee>
  idCoursSelected!:number
  etudiants:any
  coursInserer!:Reponse<Cours>

  

/*______________________________________________________constructor_______________________________________________________________*/  


  constructor(private coursService:CoursService,private formBuilder:FormBuilder){
    this.addCours = this.formBuilder.group({
      id: [''],
      module: ['',[ Validators.required]],
      professeur : ['',[Validators.required]],
      semestre : ['',[Validators.required]],
      heureTotal : ['', Validators.required],
    });
    this.addSession = this.formBuilder.group({
      id: [''],
      heureDebut: ['',[ Validators.required]],
      duree : ['',[Validators.required]],
      date : ['',[Validators.required]],
      salle_id : ['', Validators.required],
      type:[]
    });
  }
  ngOnInit(): void {
    this.coursService.semestres().subscribe(response=>{
      this.allSemestre = response
    })
    this.coursService.modules().subscribe(response=>{
      this.allModule = response
    })
    this.coursService.cours(0,2).subscribe(response=>{
      this.allCours = response
      if (this.allCours.statu == true) {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
      }
    })
    this.coursService.allAnneeClasse().subscribe(response=>{
      this.allClasseAnnee = response
    })
    this.coursService.salles().subscribe(response=>{
      this.allSalle = response
    })
  }

/*______________________________________________________Getters___________________________________________________________________*/  

  get module() {
    return this.addCours.get("libelle");
  }

  get professeur() {
    return this.addCours.get("professeur");
  }

  get semestre() {
    return this.addCours.get("semestre");
  }

  get heureTotal() {
    return this.addCours.get("heureTotal");
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
  



/*______________________________________________________Fonctions_________________________________________________________________*/  
  
  ajoutCours(){
    this.classes = []
    let res = document.querySelectorAll('.check')
    res.forEach(element => {
      let e = element as HTMLInputElement
      if (e.checked) {
        var claaseAnn =  this.allClasseAnnee.data.filter((ele:ClasseAnnee) => {
          return ((+ele.classe_id == +e.id) && (+ele.annee_scolaire_id == +this.navbar.allAnnee.data[0].id));
        });
        this.classes.push(+claaseAnn[0].id);
      }
    });
    let objet = {
      "heureTotal":+this.addCours.value.heureTotal,
      "semestre_id":+this.addCours.value.semestre,
      "module_id":+this.addCours.value.module,
      "professeur_id":+this.addCours.value.professeur,
      "classes":this.classes
    }
    this.coursService.ajoutCours(objet).subscribe(response=>{
      this.coursInserer = response
      if (response.statu == true) {
        Swal.fire({
          title:'Success',
          icon:'success',
          text:response.message
        }) 
        this.allCours.data.unshift(this.coursInserer.data[0])
        this.addCours.reset();
        res.forEach(ment => {
          let em = ment as HTMLInputElement
          if (em.checked) {
            em.checked = false
          }
        });
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

  /**
   * ajoutSession
   */
  ajoutSession() {
    this.addSession.value.salle_id = +this.addSession.value.salle_id;
    this.coursService.ajoutSession(this.addSession.value,this.idCoursSelected).subscribe(response=>{
      console.log(response);
      
    })
  }

  recupIdCours(event:Event){
    let ele = event.target as HTMLButtonElement;
    localStorage.setItem('cours_id',ele.id);
    // this.idCoursSelected = +ele.id;
  }
  
  chargerProf(){
    this.allProf = {"statu":false,"message":"","data":[]}
    this.allClasse = {"statu":false,"message":"","data":[]}
    this.coursService.professeurs(this.addCours.value.module).subscribe(response=>{
      this.allProf = response
    })
    this.coursService.classes(this.navbar.allAnnee.data[0].id).subscribe(response=>{
      this.allClasse = response
    })
  }
  

  /**
   * filtreCours
   */
  public filtreCours(event : Event) {
    const select = event.target as HTMLSelectElement;
    this.coursService.cours(this.navbar.allAnnee.data[0].id,+select.value).subscribe(response=>{
      this.allCours = response
      if (this.allCours.statu == true) {
        Swal.fire({
          position: 'top-end',
          icon: 'success',
          title: response.message,
          showConfirmButton: false,
          timer: 1500
        })
      }
    });
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

  detailCours(event : Event){
    const idCours = event.target as HTMLButtonElement;
    this.coursService.etudiantsByCours(+idCours.id).subscribe(response=>{
      this.etudiants = response
    })    
  }
}