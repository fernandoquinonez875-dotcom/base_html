import { ComponentFixture, TestBed } from '@angular/core/testing';
import { CalculadoraComponent as Calculadora } from './calculadora';

describe('Calculadora', () => {
  let component: Calculadora;
  let fixture: ComponentFixture<Calculadora>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Calculadora],
    }).compileComponents();

    fixture = TestBed.createComponent(Calculadora);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should calculate with the numbers entered in the form', async () => {
    const inputs = fixture.nativeElement.querySelectorAll('input') as NodeListOf<HTMLInputElement>;
    inputs[0].value = '12';
    inputs[0].dispatchEvent(new Event('input'));
    inputs[1].value = '3';
    inputs[1].dispatchEvent(new Event('input'));
    await fixture.whenStable();

    const buttons = fixture.nativeElement.querySelectorAll('button') as NodeListOf<HTMLButtonElement>;
    for (const [index, result] of ['15', '9', '36', '4'].entries()) {
      buttons[index].click();
      await fixture.whenStable();
      expect(fixture.nativeElement.querySelector('.valor-resultado').textContent).toContain(result);
    }

    inputs[1].value = '0';
    inputs[1].dispatchEvent(new Event('input'));
    await fixture.whenStable();
    buttons[3].click();
    await fixture.whenStable();
    expect(fixture.nativeElement.querySelector('.valor-resultado').textContent).toContain('División por cero');
  });
});
