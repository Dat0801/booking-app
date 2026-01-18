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
import { RouterLink } from '@angular/router';
import { Order, OrderService } from '../../core/services/order.service';

@Component({
  selector: 'app-order-list',
  templateUrl: './order-list.page.html',
  styleUrls: ['./order-list.page.scss'],
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
    FormsModule,
    RouterLink
  ]
})
export class OrderListPage implements OnInit {
  orders: Order[] = [];
  loading = false;

  private orderService = inject(OrderService);

  ngOnInit() {
    this.loadOrders();
  }

  loadOrders() {
    this.loading = true;

    this.orderService.getOrders().subscribe({
      next: response => {
        this.orders = response.data;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }
}
