<div class="container">
    <main>
        <div class="row mt-5">
            <div class="col-12 mb-4 text-center">
                <h4 class="text-center text-capitalize">product reviews</h4>
                @if (!(isset($_COOKIE['RemoveReviewBtn']) && $_COOKIE['RemoveReviewBtn'] == intval($_GET['pid'])))
                    <button class="rounded border-0  bg-transparent" type="button" data-bs-toggle="modal"
                        data-bs-target="#addreviewmodal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                            <path
                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                        </svg>
                    </button>
                @endif
            </div>
            <!-- carousel start -->
            <div class="col-12">
                <div class="
      swiffy-slider
      slider-nav-square slider-nav-visible slider-nav-autoplay slider-item-snapstart justify-content-center
    "
                    style="direction: ltr" data-slider-nav-autoplay-interval="5000">

                    <ul class="slider-container">
                      @foreach ($productReviews as $review)
                      <li>
                          <div class="card" style="width: 18rem;">
                              <div class="card-body text-center">
                                  <h5 class="card-title">{{ $review->fullname }}</h5>
                                  <div class="mt-1 mb-1">
                                    @for ($i = 0; $i < 5; $i++)
                                    @php
                                        $checked = (intval($review->rating)-$i)>0?'checked':'';
                                    @endphp
                                      <span class="fa fa-star {{$checked}}"></span>
                                    @endfor
                                  </div>
                                  <p class="card-text">{{$review->description}}</p>
                              </div>
                          </div>
                      </li>
                      @endforeach
                    </ul>

                    {{-- <button type="button" class="slider-nav slider-nav-square"></button>
                    <button type="button" class="slider-nav slider-nav-square slider-nav-next"></button> --}}

                    {{-- <div class="slider-indicators">
                            <button class="active"></button>
                            <button></button>
                            <button></button>
                        </div> --}}
                </div>
            </div>
        </div>
    </main>
</div>
<!-- Modal -->
<div class="modal fade" id="addreviewmodal" tabindex="-1" aria-labelledby="addreviewmodalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addreviewmodalLabel">What dyou think about this products?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action={{ $_SERVER['PHP_SELF'] }}>
                    <div class="mb-3">
                        <label for="fullnamein" class="form-label">Full Name</label>
                        <input type="text" required name="name" class="form-control" id="fullnamein"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="addrin" class="form-label">Email address</label>
                        <input type="email" required name="email" class="form-control" id="addrin">
                    </div>
                    <div class="mb-3">
                        <label for="ratingin" class="form-label">Your Rating</label>
                        <input type="number" required min="1" max="5" class="form-control" name="rating"
                            id="ratingin">
                    </div>
                    <div class="mb-3">
                        <label for="saysomin" class="form-label">Say Somthing</label>
                        <textarea required id="saysomin" name="desc" class="form-control"></textarea>
                    </div>
                    <input type="hidden" class="form-control" name="product" value={{ intval($_GET['pid']) }}>
                    <button type="submit" name="save_client_review" value="save_client_review"
                        class="btn btn-warning">Save</button>
                </form>
            </div>
        </div>
    </div>
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
