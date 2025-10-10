@extends('layouts.main')
 
@section('title', 'Page Title')

@section('content')
   @include('pages.topbar')
   {{-- {{$page}} --}}
   @if ($page!=null) 
     @include('pages.'.$page) 
   @endif 
@endsection