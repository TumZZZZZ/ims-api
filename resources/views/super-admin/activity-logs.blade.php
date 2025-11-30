@extends('layouts.app')

@section('title', __('activity_logs'))
@section('header-title', __('activity_logs'))

@section('content')

    <!-- Action bar: Search + Buttons -->
    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 25px; flex-wrap: wrap;">
        <!-- Search -->
        <input type="text" id="search" placeholder="{{ __('search') }}">
    </div>

    
@endsection
