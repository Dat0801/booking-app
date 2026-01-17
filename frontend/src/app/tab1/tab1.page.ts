import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import {
  IonHeader,
  IonToolbar,
  IonTitle,
  IonContent,
  IonSearchbar,
  IonGrid,
  IonRow,
  IonCol,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonList,
  IonItem,
  IonLabel,
  IonBadge
} from '@ionic/angular/standalone';
import { RouterLink } from '@angular/router';

interface QuickAction {
  label: string;
  link: string;
}

interface FeaturedItem {
  title: string;
  description: string;
  tag: string;
  link: string;
}

@Component({
  selector: 'app-tab1',
  templateUrl: 'tab1.page.html',
  styleUrls: ['tab1.page.scss'],
  standalone: true,
  imports: [
    IonHeader,
    IonToolbar,
    IonTitle,
    IonContent,
    IonSearchbar,
    IonGrid,
    IonRow,
    IonCol,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonList,
    IonItem,
    IonLabel,
    IonBadge,
    CommonModule,
    FormsModule,
    RouterLink
  ]
})
export class Tab1Page {
  searchQuery = '';

  quickActions: QuickAction[] = [
    { label: 'Explore tours', link: '/category-list' },
    { label: 'All services', link: '/product-list' },
    { label: 'Cart', link: '/cart' },
    { label: 'Bookings', link: '/booking-list' }
  ];

  featuredItems: FeaturedItem[] = [
    {
      title: 'Weekend beach tour',
      description: 'Enjoy a 3 days 2 nights stay at a seaside resort.',
      tag: 'Popular',
      link: '/product-list'
    },
    {
      title: 'Hotel + flight combo',
      description: 'Save money with flexible combo packages.',
      tag: 'Deal',
      link: '/product-list'
    },
    {
      title: 'Airport transfer service',
      description: 'Book private transfer, safe and on time.',
      tag: 'Convenient',
      link: '/product-list'
    }
  ];
}
