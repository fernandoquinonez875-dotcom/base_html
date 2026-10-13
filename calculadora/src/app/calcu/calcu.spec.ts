import { ComponentFixture, TestBed } from '@angular/core/testing';
import { Calcu } from './calcu';

describe('Calcu', () => {
  let component: Calcu;
  let fixture: ComponentFixture<Calcu>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Calcu],
    }).compileComponents();

    fixture = TestBed.createComponent(Calcu);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
