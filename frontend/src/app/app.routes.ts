import { Routes } from '@angular/router';

export const routes: Routes = [
  {
    path: '',
    redirectTo: 'tabs',
    pathMatch: 'full'
  },
  {
    path: '',
    loadChildren: () => import('./tabs/tabs.routes').then((m) => m.routes),
  },
  {
    path: 'login',
    loadComponent: () => import('./auth/login/login.page').then(m => m.LoginPage)
  },
  {
    path: 'register',
    loadComponent: () => import('./auth/register/register.page').then(m => m.RegisterPage)
  },
  {
    path: 'category-list',
    loadComponent: () => import('./catalog/category-list/category-list.page').then(m => m.CategoryListPage)
  },
  {
    path: 'product-list',
    loadComponent: () => import('./catalog/product-list/product-list.page').then(m => m.ProductListPage)
  },
  {
    path: 'product-detail',
    loadComponent: () => import('./catalog/product-detail/product-detail.page').then(m => m.ProductDetailPage)
  },
  {
    path: 'cart',
    loadComponent: () => import('./cart/cart/cart.page').then(m => m.CartPage)
  },
  {
    path: 'checkout',
    loadComponent: () => import('./cart/checkout/checkout.page').then(m => m.CheckoutPage)
  },
  {
    path: 'order-list',
    loadComponent: () => import('./orders/order-list/order-list.page').then(m => m.OrderListPage)
  },
  {
    path: 'order-detail',
    loadComponent: () => import('./orders/order-detail/order-detail.page').then(m => m.OrderDetailPage)
  },
  {
    path: 'booking-list',
    loadComponent: () => import('./bookings/booking-list/booking-list.page').then(m => m.BookingListPage)
  },
  {
    path: 'property-list',
    loadComponent: () => import('./bookings/property-list/property-list.page').then(m => m.PropertyListPage)
  },
  {
    path: 'booking-detail',
    loadComponent: () => import('./bookings/booking-detail/booking-detail.page').then(m => m.BookingDetailPage)
  },
  {
    path: 'booking-create',
    loadComponent: () => import('./bookings/booking-create/booking-create.page').then(m => m.BookingCreatePage)
  },
  {
    path: 'profile',
    loadComponent: () => import('./profile/profile/profile.page').then(m => m.ProfilePage)
  },
  {
    path: 'dashboard',
    loadComponent: () => import('./admin/dashboard/dashboard.page').then(m => m.DashboardPage)
  },
];
