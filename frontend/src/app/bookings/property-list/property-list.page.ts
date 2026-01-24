import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonButtons,
  IonBackButton,
  IonButton,
  IonIcon,
  IonSegment,
  IonSegmentButton,
  IonLabel,
  IonList,
  IonSpinner,
  ModalController
} from '@ionic/angular/standalone';
import { Router, ActivatedRoute } from '@angular/router';
import { addIcons } from 'ionicons';
import {
  optionsOutline,
  chevronDownOutline,
  heart,
  heartOutline,
  star,
  homeOutline
} from 'ionicons/icons';
import { PropertyService } from '../../core/services/property.service';
import { Property, PropertySearchParams } from '../../core/models/property.model';

@Component({
  selector: 'app-property-list',
  templateUrl: './property-list.page.html',
  styleUrls: ['./property-list.page.scss'],
  standalone: true,
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonButtons,
    IonBackButton,
    IonButton,
    IonIcon,
    IonSegment,
    IonSegmentButton,
    IonLabel,
    IonList,
    IonSpinner,
    CommonModule,
    FormsModule
  ]
})
export class PropertyListPage implements OnInit {
  location = 'Paris';
  guests = 2;
  dateRange = 'Jun 12 - Jun 18';
  selectedFilter = 'dates';
  loading = false;
  properties: Property[] = [];

  private router = inject(Router);
  private route = inject(ActivatedRoute);
  private modalController = inject(ModalController);
  private propertyService = inject(PropertyService);

  constructor() {
    addIcons({
      optionsOutline,
      chevronDownOutline,
      heart,
      heartOutline,
      star,
      homeOutline
    });
  }

  ngOnInit() {
    // Get query params if available
    this.route.queryParams.subscribe(params => {
      if (params['location']) this.location = params['location'];
      if (params['guests']) this.guests = +params['guests'];
      if (params['dateRange']) this.dateRange = params['dateRange'];
    });

    this.loadProperties();
  }

  loadProperties() {
    this.loading = true;

    const searchParams: PropertySearchParams = {
      location: this.location,
      guests: this.guests,
      // Add more filters as needed
    };

    this.propertyService.getProperties(searchParams).subscribe({
      next: (response) => {
        this.properties = response.data as Property[];
        this.loading = false;
      },
      error: (error) => {
        console.error('Error loading properties:', error);
        // Fallback to mock data if API fails
        this.loadMockProperties();
        this.loading = false;
      }
    });
  }

  private loadMockProperties() {
    // Fallback mock data for development
    this.properties = [
      {
        id: 1,
        name: 'Luxury Plaza Hotel',
        image: 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800',
        rating: 4.8,
        reviewCount: 1240,
        distanceFromCenter: '0.5 miles from center',
        pricePerNight: 350,
        isFavorite: true,
        type: 'Hotel'
      },
      {
        id: 2,
        name: 'Charming Marais Studio',
        image: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
        rating: 4.5,
        reviewCount: 850,
        distanceFromCenter: '1.2 miles from center',
        pricePerNight: 180,
        isFavorite: true,
        type: 'Apartment'
      },
      {
        id: 3,
        name: 'Riverside Boutique Stay',
        image: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800',
        rating: 4.9,
        reviewCount: 420,
        distanceFromCenter: '0.8 miles from center',
        pricePerNight: 290,
        isFavorite: true,
        type: 'Boutique Hotel'
      },
      {
        id: 4,
        name: 'Cozy Montmartre Loft',
        image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800',
        rating: 4.6,
        reviewCount: 320,
        distanceFromCenter: '2.1 miles from center',
        pricePerNight: 220,
        isFavorite: false,
        type: 'Apartment'
      },
      {
        id: 5,
        name: 'Grand Seine View Suite',
        image: 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800',
        rating: 4.7,
        reviewCount: 980,
        distanceFromCenter: '0.3 miles from center',
        pricePerNight: 420,
        isFavorite: false,
        type: 'Hotel'
      }
    ];
  }

  onFilterChange(event: any) {
    console.log('Filter changed:', event.detail.value);
    // Implement filter logic here
  }

  openFilters() {
    console.log('Opening filters modal');
    // Implement filter modal here
  }

  toggleFavorite(property: Property) {
    const previousState = property.isFavorite;
    property.isFavorite = !property.isFavorite;

    this.propertyService.toggleFavorite(property.id).subscribe({
      next: (response: any) => {
        property.isFavorite = response.isFavorite;
        console.log('Favorite toggled successfully');
      },
      error: (error: any) => {
        console.error('Error toggling favorite:', error);
        // Revert on error
        property.isFavorite = previousState;
      }
    });
  }

  viewProperty(property: Property) {
    console.log('Viewing property:', property.name);
    this.router.navigate(['/booking-detail'], {
      queryParams: { id: property.id }
    });
  }
}
