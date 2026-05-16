import { NgDompurifySanitizer } from "@tinkoff/ng-dompurify";
import { TuiRootModule, TuiDialogModule, TuiAlertModule, TUI_SANITIZER } from "@taiga-ui/core";
import { Component, inject } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { LayoutComponent } from './shared/layout/layout.component';
import { AuthService } from "./core/services/auth.service";

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, LayoutComponent, TuiRootModule, TuiDialogModule, TuiAlertModule],
  template: '<tui-root><app-layout><router-outlet /></app-layout></tui-root>',
    providers: [{provide: TUI_SANITIZER, useClass: NgDompurifySanitizer}]
})
export class AppComponent {

  //
   private auth = inject(AuthService);

   //initiate the current user on application startup.
  ngOnInit(): void {
    this.auth.fetchCurrentUser().subscribe();
  }
}
