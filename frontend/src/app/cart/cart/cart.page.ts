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
  IonButton
} from '@ionic/angular/standalone';
import { Router } from '@angular/router';
import { Cart, CartItem, CartService } from '../../core/services/cart.service';

@Component({
  selector: 'app-cart',
  templateUrl: './cart.page.html',
  styleUrls: ['./cart.page.scss'],
  standalone: true,
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonList,
    IonItem,
    IonLabel,
    IonButton,
    CommonModule,
    FormsModule
  ]
})
export class CartPage implements OnInit {
  cart?: Cart;
  loading = false;
  updating = false;
  clearing = false;

  private cartService = inject(CartService);
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

  increase(item: CartItem) {
    this.changeQuantity(item, item.quantity + 1);
  }

  decrease(item: CartItem) {
    const nextQuantity = item.quantity - 1;

    if (nextQuantity <= 0) {
      this.remove(item);
      return;
    }

    this.changeQuantity(item, nextQuantity);
  }

  changeQuantity(item: CartItem, quantity: number) {
    this.updating = true;

    this.cartService.updateItem(item.id, quantity).subscribe({
      next: response => {
        this.cart = response;
        this.updating = false;
      },
      error: () => {
        this.updating = false;
      }
    });
  }

  remove(item: CartItem) {
    this.updating = true;

    this.cartService.removeItem(item.id).subscribe({
      next: () => {
        this.updating = false;
        this.loadCart();
      },
      error: () => {
        this.updating = false;
      }
    });
  }

  clear() {
    if (!this.cart || !this.cart.items.length) {
      return;
    }

    this.clearing = true;

    this.cartService.clearCart().subscribe({
      next: () => {
        this.clearing = false;
        this.loadCart();
      },
      error: () => {
        this.clearing = false;
      }
    });
  }

  goToCheckout() {
    this.router.navigate(['/checkout']);
  }
}
