import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonList,
  IonItem,
  IonLabel
} from '@ionic/angular/standalone';
import { ActivatedRoute } from '@angular/router';
import { Order, OrderService } from '../../core/services/order.service';

@Component({
  selector: 'app-order-detail',
  templateUrl: './order-detail.page.html',
  styleUrls: ['./order-detail.page.scss'],
  standalone: true,
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonList,
    IonItem,
    IonLabel,
    CommonModule,
    FormsModule
  ]
})
export class OrderDetailPage implements OnInit {
  order?: Order;
  loading = false;

  private orderService = inject(OrderService);
  private route = inject(ActivatedRoute);

  ngOnInit() {
    const idParam =
      this.route.snapshot.queryParamMap.get('id') || this.route.snapshot.paramMap.get('id');

    if (idParam) {
      const id = Number(idParam);
      this.loadOrder(id);
    }
  }

  loadOrder(id: number) {
    this.loading = true;

    this.orderService.getOrder(id).subscribe({
      next: response => {
        this.order = response;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }
}
