import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfNavbarComponent } from './prof-navbar.component';

describe('ProfNavbarComponent', () => {
  let component: ProfNavbarComponent;
  let fixture: ComponentFixture<ProfNavbarComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ProfNavbarComponent]
    });
    fixture = TestBed.createComponent(ProfNavbarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
