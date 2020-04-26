import { Injectable } from '@angular/core';
import {Http, Response, Headers } from '@angular/http';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import {AuthService} from './auth.service';
import { Post } from './Post';

@Injectable({
  providedIn: 'root'
})
export class PostService {

 
  private uri= 'http://127.0.0.1/api/posts';



  constructor(private http: Http, private authenticationService: AuthService  ) {}

  getPosts(): Observable<any[]> {
    const headers = new Headers({ 'Authorization': 'Bearer ' + this.authenticationService.token });
    return  this.http.get(this.uri , {headers : headers}).pipe(map(res => <Post[]> res.json() ));

  }

  addPost(post: Post) {
    const  headers = new Headers();
    headers.append('content-type', 'application/json');
    headers.append('Authorization', 'Bearer ' + this.authenticationService.token);
    return this.http.post(this.uri, JSON.stringify(post), {headers : headers}).pipe(map(res => res.json()));
  }



  updatePost(post: Post , id) {
    const  headers = new Headers();
    headers.append('content-type', 'application/json');
    headers.append('Authorization', 'Bearer ' + this.authenticationService.token);
    return this.http.put(this.uri + '/' + id, JSON.stringify(post), {headers : headers}).pipe(map(res => res.json()));
  }


  deletePost(id: any) {
    const  headers = new Headers();
    headers.append('Authorization', 'Bearer ' + this.authenticationService.token);
    return this.http.delete(this.uri + '/' + id, {headers : headers}).pipe(map(res => res.json()));
  }
}
