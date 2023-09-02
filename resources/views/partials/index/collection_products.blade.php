@php
$products = TemplateHome::getCollectionProducts($collection);
$collection = array_shift(TemplateHome::getCollectionById($collection));
@endphp

<div class="container">
    <main>
        <div class="row mt-5">
            <div class="col-12 mb-4">
                <h4 class="text-center text-capitalize">{{ $collection->title }}</h4>
                <span class="sub_title d-block text-center">{{ $collection->sub_title }}</span>
            </div>
            <!-- carousel start -->
            <div class="col-12">
                <div class="col-12 text-center">

                    @forelse ($products as $p)
                        @php
                            $cover = explode(',', $p->images)[0];
                            $img = wp_get_attachment_image_src(intval($cover));
                            $product_link = esc_url(add_query_arg('pid', $p->id, get_permalink(get_page_by_path('Product'))));
                            // echo '<pre>';
                            // echo var_dump($img);
                            // echo '</pre>';
                        @endphp
                        <div class="card text-black d-inline-block mb-1" style="min-width: 18rem">
                            <img src="{{ $img[0] }}" class="card-img-top" alt="Apple Computer" />
                            <div class="card-body">
                                <div class="text-center">
                                    <h6 class="card-title fw-light">{{ $p->name }}</h6>
                                    {{-- <p class="text-muted mb-4">Apple pro display XDR</p> --}}
                                </div>
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-decoration-line-through"
                                            style="color: #aaa;">$6.226</span><span>$5,999</span>
                                    </div>

                                </div>
                                <div class="d-flex justify-content-between total font-weight-bold mt-4">
                                    {{-- <span>Total</span><span>$7,197.00</span> --}}
                                    <button type="button" class="btn btn-outline-success rounded-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                            <path
                                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                        </svg>
                                        <span class="text-capitalize">add to cart</span>
                                    </button>
                                    <a class="btn btn-success rounded-0" href="{{ $product_link }}"
                                      role="button">Details</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert border rounded-0 shadow-sm alert-light text-center" role="alert">
                            No Product To Show
                        </div>
                    @endforelse
                </div>

                <button type="button" class="slider-nav slider-nav-square"></button>
                <button type="button" class="slider-nav slider-nav-square slider-nav-next"></button>
            </div>
        </div>
    </main>
</div>
