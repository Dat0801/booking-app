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
import { ActivatedRoute, RouterLink } from '@angular/router';
import { ProductService, Product } from '../services/product.service';
import { CartService } from '../../core/services/cart.service';

@Component({
  selector: 'app-product-detail',
  templateUrl: './product-detail.page.html',
  styleUrls: ['./product-detail.page.scss'],
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
    FormsModule,
    RouterLink
  ]
})
export class ProductDetailPage implements OnInit {
  product?: Product;
  loading = false;
  adding = false;
  private productId?: number;

  private productService = inject(ProductService);
  private cartService = inject(CartService);
  private route = inject(ActivatedRoute);

  ngOnInit() {
    const idParam =
      this.route.snapshot.queryParamMap.get('id') || this.route.snapshot.paramMap.get('id');

    if (idParam) {
      this.productId = Number(idParam);
      this.loadProduct();
    }
  }

  loadProduct() {
    if (!this.productId) {
      return;
    }

    this.loading = true;

    this.productService.getProduct(this.productId).subscribe({
      next: response => {
        this.product = response;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }

  addToCart() {
    if (!this.product) {
      return;
    }

    this.adding = true;

    this.cartService.addItem(this.product.id, 1).subscribe({
      next: () => {
        this.adding = false;
      },
      error: () => {
        this.adding = false;
      }
    });
  }
}
