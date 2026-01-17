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
    { label: 'Khám phá tour', link: '/category-list' },
    { label: 'Tất cả dịch vụ', link: '/product-list' },
    { label: 'Giỏ hàng', link: '/cart' },
    { label: 'Đơn đặt chỗ', link: '/booking-list' }
  ];

  featuredItems: FeaturedItem[] = [
    {
      title: 'Tour biển cuối tuần',
      description: 'Trải nghiệm nghỉ dưỡng 3 ngày 2 đêm tại resort ven biển.',
      tag: 'Phổ biến',
      link: '/product-list'
    },
    {
      title: 'Combo khách sạn + vé máy bay',
      description: 'Tiết kiệm chi phí với gói combo linh hoạt.',
      tag: 'Ưu đãi',
      link: '/product-list'
    },
    {
      title: 'Dịch vụ đưa đón sân bay',
      description: 'Đặt xe riêng đưa đón tận nơi, an toàn, đúng giờ.',
      tag: 'Tiện ích',
      link: '/product-list'
    }
  ];
}
