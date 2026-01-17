import { inject } from '@angular/core';
import { HttpEvent, HttpHandlerFn, HttpInterceptorFn, HttpRequest } from '@angular/common/http';
import { Observable, from } from 'rxjs';
import { switchMap } from 'rxjs/operators';
import { StorageService } from '../services/storage.service';

export const authTokenInterceptor: HttpInterceptorFn = (
  req: HttpRequest<any>,
  next: HttpHandlerFn
): Observable<HttpEvent<any>> => {
  const storage = inject(StorageService);

  if (req.url.includes('auth/login') || req.url.includes('auth/register')) {
    return next(req);
  }

  return from(storage.getToken()).pipe(
    switchMap(token => {
      if (token) {
        const cloned = req.clone({
          setHeaders: {
            Authorization: `Bearer ${token}`
          }
        });
        return next(cloned);
      }
      return next(req);
    })
  );
};
