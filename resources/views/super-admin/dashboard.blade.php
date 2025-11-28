@extends('layouts.app')

@section('title', __('dashboard'))
@section('header-title', __('dashboard'))

@section('content')
    <div class="cards">
        <div class="card">
            <h3>{{ __('activated_merchants') }}</h3>
            <p>{{ $data['activated_merchants'] }}</p>
        </div>
        <div class="card">
            <h3>{{ __('open_branches') }}</h3>
            <p>{{ $data['open_branches'] }}</p>
        </div>
        <div class="card">
            <h3>{{ __('suspended_merchants') }}</h3>
            <p>{{ $data['suspended_merchants'] }}</p>
        </div>
        <div class="card">
            <h3>{{ __('closed_branches') }}</h3>
            <p>{{ $data['closed_branches'] }}</p>
        </div>
    </div>
    <div class="cards">
        <div class="card">
            <h3>{{ __('total_users') }}</h3>
            <p>{{ $data['total_users'] }}</p>
        </div>
        <div class="card">
            <h3>{{ __('total_admins') }}</h3>
            <p>{{ $data['total_admins'] }}</p>
        </div>
        <div class="card">
            <h3>{{ __('total_managers') }}</h3>
            <p>{{ $data['total_managers'] }}</p>
        </div>
        <div class="card">
            <h3>{{ __('total_staffs') }}</h3>
            <p>{{ $data['total_staffs'] }}</p>
        </div>
    </div>
@endsection
