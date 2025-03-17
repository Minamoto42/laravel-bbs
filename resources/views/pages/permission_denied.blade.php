@extends('layouts.app')
 @section('title', 'Permission Denied')
 
 @section('content')
     <div class="col-md-4 offset-md-4">
         <div class="card">
             <div class="card-body">
                 @if(\Illuminate\Support\Facades\Auth::check())
                     <div class="alert alert-danger text-center mb-0">
                         Current user does not have permission to access this page.
                     </div>
                 @else
                     <div class="alert alert-danger text-center">
                         Please login first.
                     </div>
 
                     <a href="{{ route('login') }}" class="btn btn-primary w-100">
                         <i class="fas fa-sign-in-alt"></i>
                         Sign in
                     </a>
                 @endif
             </div>
         </div>
     </div>
 @endsection