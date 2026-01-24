import { Component, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import {
  IonHeader,
  IonToolbar,
  IonContent,
  IonIcon
} from '@ionic/angular/standalone';

interface Category {
  id: string;
  name: string;
  active: boolean;
}

interface Destination {
  name: string;
  type: string;
  image: string;
}

interface Deal {
  name: string;
  location: string;
  rating: number;
  originalPrice: string;
  price: string;
  image: string;
}

@Component({
  selector: 'app-tab1',
  templateUrl: 'tab1.page.html',
  styleUrls: ['tab1.page.scss'],
  standalone: true,
  imports: [
    IonHeader,
    IonToolbar,
    IonContent,
    IonIcon,
    CommonModule,
    FormsModule
  ]
})
export class Tab1Page {
  private router = inject(Router);

  categories: Category[] = [
    { id: 'hotels', name: 'Hotels', active: true },
    { id: 'villas', name: 'Villas', active: false },
    { id: 'resorts', name: 'Resorts', active: false },
    { id: 'more', name: 'More', active: false }
  ];

  featuredDestinations: Destination[] = [
    {
      name: 'Santorini, Greece',
      type: 'TRENDING DESTINATION',
      image: 'https://images.unsplash.com/photo-1613395877344-13d4a8e0d49e?w=400&h=300&fit=crop'
    },
    {
      name: 'Kyoto, Japan',
      type: 'CULTURAL HUB',
      image: 'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?w=400&h=300&fit=crop'
    }
  ];

  lastMinuteDeals: Deal[] = [
    {
      name: 'Grand Hyatt Residence',
      location: 'Zurich, Switzerland',
      rating: 4.9,
      originalPrice: '#320',
      price: '$185',
      image: 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400&h=300&fit=crop'
    },
    {
      name: 'Azure Coast Villas',
      location: 'Algarve, Portugal',
      rating: 4.7,
      originalPrice: '#210',
      price: '$125',
      image: 'https://images.unsplash.com/photo-1618773928121-c1131f3ace4c?w=400&h=300&fit=crop'
    },
    {
      name: 'Studio Midtown Loft',
      location: 'London, UK',
      rating: 4.8,
      originalPrice: '#170',
      price: '$99',
      image: 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=400&h=300&fit=crop'
    }
  ];

  selectCategory(categoryId: string) {
    this.categories.forEach(cat => {
      cat.active = cat.id === categoryId;
    });
  }

  navigateToPropertyList(location?: string) {
    const queryParams: any = {};
    
    if (location) {
      queryParams.location = location;
    } else {
      queryParams.location = 'Paris';
    }
    
    queryParams.guests = 2;
    queryParams.dateRange = 'Jun 12 - Jun 18';

    this.router.navigate(['/property-list'], { queryParams });
  }
}
