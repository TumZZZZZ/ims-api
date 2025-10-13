@extends('layouts.app')

@section('title', 'Manager Dashboard')
@section('header-title', 'Stock Dashboard')

@section('content')
    <div class="cards">
        <div class="card">
            <h3>TOTAL PRODUCTS</h3>
            <p class="card-value">{{ $totalProducts ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>LOW STOCK</h3>
            <p class="card-value">{{ $lowStock ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>OUT OF STOCK</h3>
            <p class="card-value">{{ $outOfStock ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>CATEGORIES</h3>
            <p class="card-value">{{ $totalCategories ?? 0 }}</p>
        </div>
    </div>
@endsection
