import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { CoursComponent } from './cours/cours.component';
import { SessionComponent } from './session/session.component';
import { AnneeComponent } from './annee/annee.component';
import { ClasseComponent } from './classe/classe.component';
import { SalleComponent } from './salle/salle.component';
import { UserComponent } from './user/user.component';
import { ProfCoursComponent } from './prof-cours/prof-cours.component';
import { ProfSessionComponent } from './prof-session/prof-session.component';
import { AttacheSessionComponent } from './attache-session/attache-session.component';
import { AttacheDemandeAnnulationComponent } from './attache-demande-annulation/attache-demande-annulation.component';
import { LoginComponent } from './login/login.component';
import { AuthGuard } from './guard/auth.guard';
import { RoleGuard } from './guard/role.guard';
import { AttacheJustificationComponent } from './attache-justification/attache-justification.component';
import { SessionCoursComponent } from './session-cours/session-cours.component';
import { AbsenceComponent } from './absence/absence.component';
//role @2 prof @3 attache @4 admin @1 super etudiant
enum Role {
  Prof = 2,
  Attache = 3,
  Admin = 4,
  Etudiant = 1
}
const routes: Routes = [
  {path:'',component:LoginComponent},
  {path:'cours',component:CoursComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Admin
  }},
  {path:'session',component:SessionComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Admin
  }},
  {path:'annee',component:AnneeComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Admin
  }},
  {path:'classe',component:ClasseComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Admin
  }},
  {path:'salle',component:SalleComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Admin
  }},
  {path:'user',component:UserComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Admin
  }},
  {path:'prof/cours',component:ProfCoursComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Prof
  }},
  {path:'prof/session',component:ProfSessionComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Prof
  }},
  {path:'attache/session',component:AttacheSessionComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Attache
  }},
  {path:'attache/demande',component:AttacheDemandeAnnulationComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Attache
  }},
  {path:'attache/justification',component:AttacheJustificationComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Attache
  }},
  {path:'absence',component:AbsenceComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Attache
  }},
  {path:'session/cours',component:SessionCoursComponent,canActivate: [AuthGuard,RoleGuard],
  data:{
    role:Role.Admin
  }},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
