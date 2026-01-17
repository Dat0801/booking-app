import { Injectable } from '@angular/core';
import { ApiService } from './api.service';
import { StorageService } from './storage.service';
import { Observable, tap } from 'rxjs';

interface LoginResponse {
  token: string;
  user: {
    id: number;
    name: string;
    email: string;
    roles: string[];
  };
}

@Injectable({ providedIn: 'root' })
export class AuthService {
  private currentUser: LoginResponse['user'] | null = null;

  constructor(private api: ApiService, private storage: StorageService) {}

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

  async logout(): Promise<void> {
    try {
      await this.api.post('auth/logout', {}).toPromise();
    } catch {}
    await this.storage.clearToken();
    this.currentUser = null;
  }

  getUser() {
    return this.currentUser;
  }
}