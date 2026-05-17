import { ChangeDetectionStrategy, Component, EventEmitter, Input, Output } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { TuiTextfieldControllerModule } from '@taiga-ui/core';
import { TuiInputSliderModule, TuiPaginationModule } from '@taiga-ui/kit';

@Component({
    selector: 'app-pagination',
    standalone: true,
    imports: [
        CommonModule,
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
    @Input() index = 0; // 0-indexed page number
    @Input() length = 1; // total number of pages

    @Output() indexChange = new EventEmitter<number>();

    sidePadding = 3;

    onIndexChange(newIndex: number): void {
        this.indexChange.emit(newIndex);
    }
}
