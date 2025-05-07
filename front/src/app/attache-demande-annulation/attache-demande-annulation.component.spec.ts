import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AttacheDemandeAnnulationComponent } from './attache-demande-annulation.component';

describe('AttacheDemandeAnnulationComponent', () => {
  let component: AttacheDemandeAnnulationComponent;
  let fixture: ComponentFixture<AttacheDemandeAnnulationComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [AttacheDemandeAnnulationComponent]
    });
    fixture = TestBed.createComponent(AttacheDemandeAnnulationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
