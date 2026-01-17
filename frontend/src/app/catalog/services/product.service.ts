import { Injectable } from '@angular/core';
import { ApiService } from '../../core/services/api.service';
import { Observable } from 'rxjs';

export interface Product {
  id: number;
  name: string;
  type: 'product' | 'service';
  price: string;
  image_url?: string;
}

@Injectable({ providedIn: 'root' })
export class ProductService {
  constructor(private api: ApiService) {}

  getProducts(params?: any): Observable<{ data: Product[] }> {
    return this.api.get<{ data: Product[] }>('products', params);
  }

  getProduct(id: number): Observable<Product> {
    return this.api.get<Product>(`products/${id}`);
  }
}