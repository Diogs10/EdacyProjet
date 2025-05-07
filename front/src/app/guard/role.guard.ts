import { Injectable } from '@angular/core';
import {
  ActivatedRouteSnapshot,
  RouterStateSnapshot,
  Router,
  UrlTree,
} from '@angular/router';
import { Observable } from 'rxjs';
import { LoginService } from '../services/login.service';
import { parse } from 'date-fns';

@Injectable({
  providedIn: 'root',
})
export class RoleGuard {
  constructor(public loginService: LoginService, public router: Router) {}

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Observable<boolean> | Promise<boolean> | UrlTree | boolean {
    let role = next.data['role']
    let rout = state.url
    
    let user = JSON.parse(this.loginService.getUser()!)
    if (user.role_id != role) {
      // window.alert('Access Denied, Login is Required to Access This Page!');
      this.router.navigate([`${rout}`]);
      return false;
    }
    return true;
  }
}