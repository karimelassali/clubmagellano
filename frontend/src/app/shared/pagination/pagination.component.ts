import {ChangeDetectionStrategy, Component} from '@angular/core';
import {FormsModule} from '@angular/forms';
import {TuiTextfieldControllerModule} from '@taiga-ui/core';
import {TuiInputSliderModule, TuiPaginationModule} from '@taiga-ui/kit';

@Component({
    selector: 'app-pagination',
    standalone: true,
    imports: [
        FormsModule,
        TuiInputSliderModule,
        TuiPaginationModule,
        TuiTextfieldControllerModule,
    ],
    templateUrl: './pagination.component.html',
    styleUrls: ['./pagination.component.css'],
    changeDetection: ChangeDetectionStrategy.OnPush,
})
export class PaginationComponent {
    sidePadding = 3;
}
