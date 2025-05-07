import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ProfSessionComponent } from './prof-session.component';

describe('ProfSessionComponent', () => {
  let component: ProfSessionComponent;
  let fixture: ComponentFixture<ProfSessionComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ProfSessionComponent]
    });
    fixture = TestBed.createComponent(ProfSessionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
