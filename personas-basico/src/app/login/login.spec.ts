import { ComponentFixture, TestBed } from '@angular/core/testing';
import { Login } from './login';

describe('Login', () => {
  let component: Login;
  let fixture: ComponentFixture<Login>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [Login],
    }).compileComponents();

    fixture = TestBed.createComponent(Login);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should sign in from the form and clear the session when signing out', async () => {
    localStorage.removeItem('sesionBasica');
    const usuario = fixture.nativeElement.querySelector('#usuario') as HTMLInputElement;
    const clave = fixture.nativeElement.querySelector('#clave') as HTMLInputElement;
    usuario.value = 'admin';
    usuario.dispatchEvent(new Event('input'));
    clave.value = '123456';
    clave.dispatchEvent(new Event('input'));
    await fixture.whenStable();
    fixture.nativeElement.querySelector('form').dispatchEvent(new Event('submit', { cancelable: true }));
    await fixture.whenStable();

    expect(localStorage.getItem('sesionBasica')).toBe('si');
    expect(fixture.nativeElement.textContent).toContain('Sesión iniciada correctamente');
    expect(component.clave).toBe('');

    fixture.nativeElement.querySelector('button').click();
    await fixture.whenStable();
    expect(localStorage.getItem('sesionBasica')).toBeNull();
    expect(fixture.nativeElement.querySelector('form')).toBeTruthy();
    expect(component.usuario).toBe('');
  });
});
