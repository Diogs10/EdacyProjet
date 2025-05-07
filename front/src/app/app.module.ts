import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { CoursComponent } from './cours/cours.component';
import { ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { NavbarComponent } from './navbar/navbar.component';
import { CalendarModule, DateAdapter } from 'angular-calendar';
import { adapterFactory } from 'angular-calendar/date-adapters/date-fns';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { SessionComponent } from './session/session.component';
import { FullCalendarModule } from '@fullcalendar/angular';
import { AnneeComponent } from './annee/annee.component';
import { ClasseComponent } from './classe/classe.component';
import { SweetAlert2Module } from '@sweetalert2/ngx-sweetalert2';
import {NgxPaginationModule} from 'ngx-pagination';
import { SalleComponent } from './salle/salle.component';
import { UserComponent } from './user/user.component';
import { ProfCoursComponent } from './prof-cours/prof-cours.component';
import { ProfSessionComponent } from './prof-session/prof-session.component';
import { ProfNavbarComponent } from './prof-navbar/prof-navbar.component';
import { AttacheSessionComponent } from './attache-session/attache-session.component';
import { AttacheNavBarComponent } from './attache-nav-bar/attache-nav-bar.component';
import { AttacheDemandeAnnulationComponent } from './attache-demande-annulation/attache-demande-annulation.component';
import { LoginComponent } from './login/login.component';
import { authInterceptorProviders } from './__helpers/auth.interceptor';
import { AttacheJustificationComponent } from './attache-justification/attache-justification.component';
import { SessionCoursComponent } from './session-cours/session-cours.component';
import { AbsenceComponent } from './absence/absence.component';

@NgModule({
  declarations: [
    AppComponent,
    CoursComponent,
    NavbarComponent,
    SessionComponent,
    AnneeComponent,
    ClasseComponent,
    SalleComponent,
    UserComponent,
    ProfCoursComponent,
    ProfSessionComponent,
    ProfNavbarComponent,
    AttacheSessionComponent,
    AttacheNavBarComponent,
    AttacheDemandeAnnulationComponent,
    LoginComponent,
    AttacheJustificationComponent,
    SessionCoursComponent,
    AbsenceComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    FullCalendarModule,
    AppRoutingModule,
    ReactiveFormsModule,
    HttpClientModule,
    CalendarModule.forRoot({ provide: DateAdapter, useFactory: adapterFactory }),
    SweetAlert2Module.forRoot(),
    SweetAlert2Module,
    SweetAlert2Module.forChild({ /* options */ }),
    NgxPaginationModule
  ],
  providers: [authInterceptorProviders],
  bootstrap: [AppComponent]
})
export class AppModule { }
