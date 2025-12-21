@extends('layouts.app')

@section('title', 'Dashboard')
@section('header-title', 'Dashboard')

@section('content')
    <div class="cards">
        <div class="card">
            <h3>ORDERS</h3>
            <p class="card-value">{{ $totalOrders ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>REVENUE</h3>
            <p class="card-value">${{ number_format($totalRevenue ?? 0, 2) }}</p>
        </div>
        <div class="card">
            <h3>EXPENSE</h3>
            <p class="card-value">${{ number_format($totalExpenses ?? 0, 2) }}</p>
        </div>
        <div class="card">
            <h3>PROFIT</h3>
            <p class="card-value">${{ number_format($totalProfit ?? 0, 2) }}</p>
        </div>
    </div>
@endsection
