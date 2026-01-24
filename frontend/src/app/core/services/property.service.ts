import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';
import { Property, PropertySearchParams, PropertyResponse } from '../models/property.model';

@Injectable({
  providedIn: 'root'
})
export class PropertyService {
  private http = inject(HttpClient);
  private apiUrl = environment.apiBaseUrl || 'http://localhost:8000/api';

  /**
   * Get list of properties with optional filters
   */
  getProperties(params?: PropertySearchParams): Observable<PropertyResponse> {
    let httpParams = new HttpParams();

    if (params) {
      Object.keys(params).forEach(key => {
        const value = (params as any)[key];
        if (value !== undefined && value !== null) {
          if (Array.isArray(value)) {
            value.forEach(v => httpParams = httpParams.append(key, v));
          } else {
            httpParams = httpParams.set(key, value.toString());
          }
        }
      });
    }

    return this.http.get<PropertyResponse>(`${this.apiUrl}/properties`, { params: httpParams });
  }

  /**
   * Get property details by ID
   */
  getPropertyById(id: number): Observable<{ success: boolean; data: Property }> {
    return this.http.get<{ success: boolean; data: Property }>(`${this.apiUrl}/properties/${id}`);
  }

  /**
   * Toggle property favorite status
   */
  toggleFavorite(propertyId: number): Observable<{ success: boolean; isFavorite: boolean }> {
    return this.http.post<{ success: boolean; isFavorite: boolean }>(
      `${this.apiUrl}/properties/${propertyId}/favorite`,
      {}
    );
  }

  /**
   * Get user's favorite properties
   */
  getFavorites(): Observable<PropertyResponse> {
    return this.http.get<PropertyResponse>(`${this.apiUrl}/properties/favorites`);
  }

  /**
   * Search properties by text
   */
  searchProperties(query: string): Observable<PropertyResponse> {
    return this.http.get<PropertyResponse>(`${this.apiUrl}/properties/search`, {
      params: { q: query }
    });
  }

  /**
   * Get available property types
   */
  getPropertyTypes(): Observable<{ success: boolean; data: string[] }> {
    return this.http.get<{ success: boolean; data: string[] }>(`${this.apiUrl}/properties/types`);
  }

  /**
   * Get available amenities
   */
  getAmenities(): Observable<{ success: boolean; data: string[] }> {
    return this.http.get<{ success: boolean; data: string[] }>(`${this.apiUrl}/properties/amenities`);
  }

  /**
   * Check property availability for dates
   */
  checkAvailability(propertyId: number, checkIn: string, checkOut: string): Observable<{
    success: boolean;
    available: boolean;
    price?: number;
  }> {
    return this.http.post<{ success: boolean; available: boolean; price?: number }>(
      `${this.apiUrl}/properties/${propertyId}/check-availability`,
      { checkIn, checkOut }
    );
  }
}
