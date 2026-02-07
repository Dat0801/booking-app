import { Component, OnInit, inject, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
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
  IonIcon,
  IonGrid,
  IonRow,
  IonCol,
  IonBadge,
  IonSpinner,
  IonCard,
  IonCardContent
} from '@ionic/angular/standalone';
import { ActivatedRoute, Router, RouterLink } from '@angular/router';
import { ProductService, Product, Amenity, Review } from '../services/product.service';
import { CartService } from '../../core/services/cart.service';
import { addIcons } from 'ionicons';
import { register } from 'swiper/element/bundle';
import {
  heart,
  share,
  arrowBack,
  cartOutline,
  star,
  locationOutline,
  wifiOutline,
  waterOutline,
  snowOutline,
  carOutline,
  restaurantOutline,
  tvOutline,
  checkmarkCircleOutline
} from 'ionicons/icons';

@Component({
  selector: 'app-product-detail',
  templateUrl: './product-detail.page.html',
  styleUrls: ['./product-detail.page.scss'],
  standalone: true,
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
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
    IonGrid,
    IonRow,
    IonCol,
    IonBadge,
    IonSpinner,
    IonCard,
    IonCardContent,
    CommonModule,
    FormsModule,
    RouterLink
  ]
})
export class ProductDetailPage implements OnInit {
  product?: Product;
  loading = false;
  adding = false;
  isFavorite = false;
  activeSlide = 0;

  private productId?: number;
  private productService = inject(ProductService);
  private cartService = inject(CartService);
  private route = inject(ActivatedRoute);
  private router = inject(Router);

  constructor() {
    register();
    addIcons({
      heart,
      share,
      arrowBack,
      cartOutline,
      star,
      locationOutline,
      wifiOutline,
      waterOutline,
      snowOutline,
      carOutline,
      restaurantOutline,
      tvOutline,
      checkmarkCircleOutline
    });
  }

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
        this.router.navigate(['/cart']);
      },
      error: () => {
        this.adding = false;
      }
    });
  }

  toggleFavorite() {
    this.isFavorite = !this.isFavorite;
  }

  onSlideChange(event: any) {
    this.activeSlide = event.detail[0].realIndex;
  }

  goToBooking() {
    if (!this.product) {
      return;
    }
    this.router.navigate(['/booking-create'], {
      queryParams: { product_id: this.product.id }
    });
  }

  getAmenityIcon(amenityName: string): string {
    const name = amenityName.toLowerCase();
    if (name.includes('wifi')) return 'wifi-outline';
    if (name.includes('pool')) return 'water-outline';
    if (name.includes('ac') || name.includes('air')) return 'snow-outline';
    if (name.includes('park')) return 'car-outline';
    if (name.includes('kitchen')) return 'restaurant-outline';
    if (name.includes('tv') || name.includes('hdtv')) return 'tv-outline';
    return 'checkmark-circle-outline';
  }

  get totalImages(): number {
    return (this.product?.gallery?.length || 0) + (this.product?.image_url ? 1 : 0);
  }

  get displayRating(): string {
    return this.product?.rating ? this.product.rating.toFixed(1) : '0';
  }

  get starArray(): number[] {
    const rating = this.product?.rating || 0;
    return Array.from({ length: 5 }, (_, i) => i < Math.round(rating) ? 1 : 0);
  }
}
