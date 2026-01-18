import { Injectable, inject } from '@angular/core';
import { ApiService } from './api.service';
import { StorageService } from './storage.service';
import { Observable, tap } from 'rxjs';

export interface AuthUser {
  id: number;
  name: string;
  email: string;
  roles: string[];
}

interface LoginResponse {
  token: string;
  user: AuthUser;
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  private currentUser: AuthUser | null = null;
  private api = inject(ApiService);
  private storage = inject(StorageService);

  login(email: string, password: string): Observable<LoginResponse> {
    return this.api.post<LoginResponse>('auth/login', { email, password }).pipe(
      tap(async response => {
        await this.storage.setToken(response.token);
        this.currentUser = response.user;
      })
    );
  }

  register(data: { name: string; email: string; password: string }): Observable<LoginResponse> {
    return this.api.post<LoginResponse>('auth/register', data).pipe(
      tap(async response => {
        await this.storage.setToken(response.token);
        this.currentUser = response.user;
      })
    );
  }

  fetchCurrentUser(): Observable<AuthUser> {
    return this.api.get<AuthUser>('auth/me').pipe(
      tap(user => {
        this.currentUser = user;
      })
    );
  }

  async logout(): Promise<void> {
    try {
      await this.api.post('auth/logout', {}).toPromise();
    } catch {}
    await this.storage.clearToken();
    this.currentUser = null;
  }

  getUser(): AuthUser | null {
    return this.currentUser;
  }

  isLoggedIn(): boolean {
    return !!this.currentUser;
  }
}
