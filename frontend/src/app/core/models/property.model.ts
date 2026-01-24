export interface Property {
  id: number;
  name: string;
  description?: string;
  image: string;
  images?: string[];
  rating: number;
  reviewCount: number;
  distanceFromCenter: string;
  pricePerNight: number;
  isFavorite: boolean;
  type: string;
  amenities?: string[];
  address?: string;
  location?: PropertyLocation;
  host?: PropertyHost;
  maxGuests?: number;
  bedrooms?: number;
  bathrooms?: number;
  cancellationPolicy?: string;
}

export interface PropertyLocation {
  latitude: number;
  longitude: number;
  city?: string;
  country?: string;
}

export interface PropertyHost {
  id: number;
  name: string;
  image: string;
  bio?: string;
  responseRate?: number;
  responseTime?: string;
  verified?: boolean;
}

export interface PropertySearchParams {
  location?: string;
  checkIn?: string;
  checkOut?: string;
  guests?: number;
  minPrice?: number;
  maxPrice?: number;
  type?: string;
  amenities?: string[];
  page?: number;
  limit?: number;
  sortBy?: 'price' | 'rating' | 'distance';
  sortOrder?: 'asc' | 'desc';
}

export interface PropertyResponse {
  success: boolean;
  data: Property[];
  meta?: PropertyResponseMeta;
}

export interface PropertyResponseMeta {
  total: number;
  page: number;
  limit: number;
  totalPages?: number;
}

export interface PropertyReview {
  id: number;
  propertyId: number;
  userId: number;
  userName: string;
  userImage?: string;
  rating: number;
  comment: string;
  createdAt: string;
  helpful?: number;
}

export interface PropertyAvailability {
  date: string;
  available: boolean;
  price?: number;
  minStay?: number;
}
