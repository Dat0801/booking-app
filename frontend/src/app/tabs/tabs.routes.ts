import { Routes } from '@angular/router';
import { TabsPage } from './tabs.page';

export const routes: Routes = [
  {
    path: 'tabs',
    component: TabsPage,
    children: [
      {
        path: 'tab1',
        loadComponent: () =>
          import('../tab1/tab1.page').then((m) => m.Tab1Page),
      },
      {
        path: 'bookings',
        loadComponent: () =>
          import('../bookings/booking-list/booking-list.page').then((m) => m.BookingListPage),
      },
      {
        path: 'catalog',
        loadComponent: () =>
          import('../catalog/product-list/product-list.page').then((m) => m.ProductListPage),
      },
      {
        path: 'account',
        loadComponent: () =>
          import('../profile/profile/profile.page').then((m) => m.ProfilePage),
      },
      {
        path: '',
        redirectTo: '/tabs/tab1',
        pathMatch: 'full',
      },
    ],
  },
  {
    path: '',
    redirectTo: '/tabs/tab1',
    pathMatch: 'full',
  },
];
