import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AttacheJustificationComponent } from './attache-justification.component';

describe('AttacheJustificationComponent', () => {
  let component: AttacheJustificationComponent;
  let fixture: ComponentFixture<AttacheJustificationComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [AttacheJustificationComponent]
    });
    fixture = TestBed.createComponent(AttacheJustificationComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
