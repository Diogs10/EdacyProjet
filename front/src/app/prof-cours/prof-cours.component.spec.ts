import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfCoursComponent } from './prof-cours.component';

describe('ProfCoursComponent', () => {
  let component: ProfCoursComponent;
  let fixture: ComponentFixture<ProfCoursComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ProfCoursComponent]
    });
    fixture = TestBed.createComponent(ProfCoursComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
