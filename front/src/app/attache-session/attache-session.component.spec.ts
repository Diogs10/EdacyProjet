import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AttacheSessionComponent } from './attache-session.component';

describe('AttacheSessionComponent', () => {
  let component: AttacheSessionComponent;
  let fixture: ComponentFixture<AttacheSessionComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [AttacheSessionComponent]
    });
    fixture = TestBed.createComponent(AttacheSessionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
