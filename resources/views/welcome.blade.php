@extends('layouts.frontend')

@section('title', 'Travelilly - Plan Your Perfect Journey')

@section('content')
    <!-- Hero Section -->
    @include('components.frontend.hero')

    <!-- Features Section -->
    @include('components.frontend.features')

    <!-- Destinations Section -->
    @include('components.frontend.destinations')

    <!-- How It Works Section -->
    @include('components.frontend.how-it-works')

    <!-- Testimonials Section -->
    @include('components.frontend.testimonials')
@endsection
