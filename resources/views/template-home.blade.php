{{-- Template Name: Home Template
  --}}

@extends('layouts.app')

@section('content')
    @php
    $collection = isset($_GET['collection']) ? intval($_GET['collection']) : 0;
    // $menuName = wp_get_theme()->name . ' Header Menu';
    // $menu = wp_get_nav_menu_object($menuName);
    // // wp_get_nav_menu_items(4);
    // echo '<pre>';
    // echo var_dump($get_footer_menu);
    // echo '</pre>';
    @endphp
    @if ($collection > 0)
        {{-- collection products --}}
        @include('partials.index.collection_products')
    @else
        <!-- slider -->
        @include('partials.index.slider')
        <!-- shop categories and items -->
        @include('partials.index.products')
        <!-- social links -->
        {{-- @include('partials.index.social') --}}
        <!-- store specialities -->
        {{-- @include('partials.index.special') --}}
    @endif


@endsection
