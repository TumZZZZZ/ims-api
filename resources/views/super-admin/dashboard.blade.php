@extends('layouts.app')

@section('title', 'Dashboard')
@section('header-title', 'Dashboard')

@section('content')
    <div class="cards">
        <div class="card">
            <h3>STORES</h3>
            <p>{{ $data['total_stores'] }}</p>
        </div>
        <div class="card">
            <h3>ADMINS</h3>
            <p>{{ $data['total_admins'] }}</p>
        </div>
        <div class="card">
            <h3>MANAGERS</h3>
            <p>{{ $data['total_managers'] }}</p>
        </div>
        <div class="card">
            <h3>STAFFS</h3>
            <p>{{ $data['total_staffs'] }}</p>
        </div>
    </div>
@endsection
