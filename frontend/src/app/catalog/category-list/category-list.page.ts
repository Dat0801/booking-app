import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonContent, IonHeader, IonList, IonItem, IonLabel, IonTitle, IonToolbar } from '@ionic/angular/standalone';
import { RouterLink } from '@angular/router';
import { ProductService, Category } from '../services/product.service';

@Component({
  selector: 'app-category-list',
  templateUrl: './category-list.page.html',
  styleUrls: ['./category-list.page.scss'],
  standalone: true,
  imports: [IonContent, IonHeader, IonTitle, IonToolbar, IonList, IonItem, IonLabel, CommonModule, FormsModule, RouterLink]
})
export class CategoryListPage implements OnInit {

  categories: Category[] = [];

  loading = false;

  private productService = inject(ProductService);

  ngOnInit() {
    this.loadCategories();
  }

  loadCategories() {
    this.loading = true;

    this.productService.getCategories().subscribe({
      next: response => {
        this.categories = response.data;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }

}
