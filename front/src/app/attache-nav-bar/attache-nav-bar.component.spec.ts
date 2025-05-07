import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AttacheNavBarComponent } from './attache-nav-bar.component';

describe('AttacheNavBarComponent', () => {
  let component: AttacheNavBarComponent;
  let fixture: ComponentFixture<AttacheNavBarComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [AttacheNavBarComponent]
    });
    fixture = TestBed.createComponent(AttacheNavBarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
