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
  IonLabel,
  IonInput,
  IonTextarea,
  IonButton
} from '@ionic/angular/standalone';
import { Router } from '@angular/router';
import { Cart, CartService } from '../../core/services/cart.service';
import { Order, OrderService } from '../../core/services/order.service';

@Component({
  selector: 'app-checkout',
  templateUrl: './checkout.page.html',
  styleUrls: ['./checkout.page.scss'],
  standalone: true,
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonList,
    IonItem,
    IonLabel,
    IonInput,
    IonTextarea,
    IonButton,
    CommonModule,
    FormsModule
  ]
})
export class CheckoutPage implements OnInit {
  cart?: Cart;
  paymentMethod = '';
  notes = '';
  submitting = false;
  loading = false;

  private cartService = inject(CartService);
  private orderService = inject(OrderService);
  private router = inject(Router);

  ngOnInit() {
    this.loadCart();
  }

  loadCart() {
    this.loading = true;

    this.cartService.getCart().subscribe({
      next: response => {
        this.cart = response;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }

  submit() {
    if (!this.cart) {
      return;
    }

    this.submitting = true;

    this.orderService
      .createOrder(this.cart.id, this.paymentMethod || undefined, this.notes || undefined)
      .subscribe({
        next: (order: Order) => {
          this.submitting = false;
          this.router.navigate(['/order-detail'], { queryParams: { id: order.id } });
        },
        error: () => {
          this.submitting = false;
        }
      });
  }
}
