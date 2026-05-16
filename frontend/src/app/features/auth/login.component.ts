import { Component, signal } from '@angular/core';
// import { FormsModule } from '@angular/forms';

//Using Form Builder and Reactive Forms for better forms handling and validation.
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators
} from '@angular/forms';

import { Router } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';
import { inject } from '@angular/core';
import { SpinnerComponent } from '../../shared/spinner/spinner.component';

@Component({
  selector: 'app-login',
  standalone: true,

  //We using Reactive Forms instead of Template Driven Forms 
  //becuase it's more powerful and maintainable.
  imports: [ReactiveFormsModule, SpinnerComponent],
  styleUrl: "./login.component.css",

  //Using A seperated html comp for clean code and better scalability.
  templateUrl: "./login.component.html"
})
export class LoginComponent {
  loading = signal(false);
  message = '';

  // email = 'senior@example.com';
  // password = 'password';
 



  private fb = inject(FormBuilder);


  constructor(private auth: AuthService, private router: Router) {}


  //Improved Validation...
  loginForm: FormGroup = this.fb.group({
    email: ['', [Validators.required, Validators.email]],
    password: ['', Validators.required]
  });

  submit() {

    //If form is invalid, show error message and return.
    if(this.loginForm.invalid){
      this.message = 'Per favore inserisci email e password';
      return;
    }

    //Set loading to true and show spinner.
    this.loading.set(true);
    
    const {email,password} = this.loginForm.value;
    this.auth.login(email, password).subscribe({
      next: (response) => {  
        this.message = 'Login eseguito';
        this.router.navigateByUrl('/');
      },
      //pass error message to message
      error: (error) => {
        //show the backend error if available or error login if not.
        this.message =  error.error.message || error;
        this.loading.set(false);
      }
    });
  }
}
