@extends('layouts.app')

@section('title', 'Dashboard')
@section('header-title', 'Dashboard')

@section('content')
    <div class="cards">
        <div class="card">
            <h3>STORES</h3>
            <p>450</p>
        </div>
        <div class="card">
            <h3>ADMINS</h3>
            <p>1,245</p>
        </div>
        <div class="card">
            <h3>MANAGERS</h3>
            <p>87</p>
        </div>
        <div class="card">
            <h3>STAFFS</h3>
            <p>14</p>
        </div>
    </div>
@endsection
