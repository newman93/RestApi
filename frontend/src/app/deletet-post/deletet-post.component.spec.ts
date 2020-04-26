import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DeletetPostComponent } from './deletet-post.component';

describe('DeletetPostComponent', () => {
  let component: DeletetPostComponent;
  let fixture: ComponentFixture<DeletetPostComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DeletetPostComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DeletetPostComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
