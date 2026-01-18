import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import {
  IonContent,
  IonHeader,
  IonButtons,
  IonBackButton,
  IonTitle,
  IonToolbar,
  IonList,
  IonItem,
  IonLabel,
  IonButton,
  IonIcon
} from '@ionic/angular/standalone';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { ProductService, Product } from '../services/product.service';

@Component({
  selector: 'app-product-list',
  templateUrl: './product-list.page.html',
  styleUrls: ['./product-list.page.scss'],
  standalone: true,
  imports: [
    IonContent,
    IonHeader,
    IonButtons,
    IonBackButton,
    IonTitle,
    IonToolbar,
    IonList,
    IonItem,
    IonLabel,
    IonButton,
    IonIcon,
    CommonModule,
    FormsModule,
    RouterLink
  ]
})
export class ProductListPage implements OnInit {
  products: Product[] = [];
  loading = false;
  categoryId?: number;
  loadError = false;

  private productService = inject(ProductService);
  private route = inject(ActivatedRoute);

  ngOnInit() {
    const categoryParam =
      this.route.snapshot.queryParamMap.get('category_id') ||
      this.route.snapshot.paramMap.get('category_id');

    if (categoryParam) {
      this.categoryId = Number(categoryParam);
    }

    this.loadProducts();
  }

  loadProducts() {
    this.loading = true;
    this.loadError = false;

    const params: any = {};

    if (this.categoryId) {
      params.category_id = this.categoryId;
    }

    this.productService.getProducts(params).subscribe({
      next: response => {
        this.products = response.data;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
        this.loadError = true;
      }
    });
  }
}
