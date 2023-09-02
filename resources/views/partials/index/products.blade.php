<div class="container">
    <main>
        @foreach ($get_collections as $collection)

            <div class="row mt-5">
                <div class="col-12 mb-4">
                    <h4 class="text-center text-capitalize">{{ $collection->title }}</h4>
                    <span class="sub_title d-block text-center">{{ $collection->sub_title }}</span>
                </div>
                <!-- carousel start -->
                <div class="col-12">
                    <div class="
      swiffy-slider
      slider-nav-square slider-nav-visible slider-nav-autoplay slider-item-snapstart justify-content-center
    "
                        style="direction: ltr" data-slider-nav-autoplay-interval="5000">

                        <ul class="slider-container">
                            @foreach ($collection->products as $p)
                                @php
                                    $cover = explode(',', $p->images)[0];
                                    $img = wp_get_attachment_image_src(intval($cover));
                                    // $product_link = get_permalink() . 'p=' . ;
                                    $product_link = esc_url(add_query_arg('pid', $p->id, get_permalink(get_page_by_path('Product'))));
                                    // echo '<pre>';
                                    // echo var_dump($img);
                                    // echo '</pre>';
                                @endphp
                                <li>
                                    <div class="card text-black" style="max-width: 100%; height: auto">
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
                                                {{-- <div class="d-flex justify-content-between">
                                          <span>Pro stand</span><span>$999</span>
                                      </div>
                                      <div class="d-flex justify-content-between">
                                          <span>Vesa Mount Adapter</span><span>$199</span>
                                      </div> --}}
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
                                </li>
                            @endforeach
                        </ul>

                        <button type="button" class="slider-nav slider-nav-square"></button>
                        <button type="button" class="slider-nav slider-nav-square slider-nav-next"></button>

                        {{-- <div class="slider-indicators">
                            <button class="active"></button>
                            <button></button>
                            <button></button>
                        </div> --}}
                    </div>
                </div>
            </div>
        @endforeach
    </main>
</div>
<script>
    var clientWidth = document.documentElement.clientWidth;
    var show = "";
    var elem = document.getElementsByClassName("swiffy-slider");


    // alert(clientWidth);
    switch (true) {
        case (clientWidth >= 576 && clientWidth <= 768):
            show = "slider-item-show2"
            break;
        case (clientWidth >= 768 && clientWidth <= 992):
            show = "slider-item-show3"
            break;
        case (clientWidth >= 1200 && clientWidth <= 1400):
            show = "slider-item-show4"
            break;
    }
    // alert(show);
    if (show == "") {
        // show.
    }
    // console.log(document.getElementsByClassName("swiffy-slider"));
    for (var i = 0, all = elem.length; i < all; i++) {
        var products = elem[i].getElementsByTagName("li");
        elem[i].classList.add(show);
        // alert(products.length);
    }
</script>
