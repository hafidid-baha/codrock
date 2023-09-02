{{-- Template Name: Collection Template
  --}}
@php
// echo '<pre>';
// echo print_r(home_url('/'));
// echo '</pre>';
@endphp
@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div
            class="
      row
      justify-content-center
      row-cols-1 row-cols-sm-1 row-cols-md-3 row-cols-lg-4 row-cols-xl-5
      mt-3
    ">
            @foreach ($get_collections as $col)
                @php
                    $collection_cover = isset($col) && $col->image > 0 ? wp_get_attachment_image_src($col->image, [354, 354])[0] : $get_default_cover;
                @endphp
                <div class="col mt-3">
                    <div class="card rounded-0">
                        <img src="@asset('images/loader.gif')" data-src="{{ $collection_cover }}"
                            class="card-img-top lazyload" />
                        <div class="card-body p-1">
                            <hr />
                            <a href=@php
                                $colid = $col->id;
                                echo home_url("/?collection=$colid");
                            @endphp
                                class="
                                p-2
    category_btn
    text-dark text-decoration-none
    d-block
    text-center
  ">{{ $col->title }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
