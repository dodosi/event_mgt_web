@extends('layouts.main')

@section('title', 'Page Title')

@section('content')

  @if(!Session::get('LoggedUser'))
     @include('pages.topbarnone')
  @endif
  @if(Session::get('LoggedUser'))
    @include('pages.topbar')
  @endif
   {{-- {{$page}} --}}
   @if ($page!=null)
     @include('pages.'.$page)
   @endif
@endsection
