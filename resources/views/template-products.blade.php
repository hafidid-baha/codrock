{{-- Template Name: Product Template
  --}}
@php
if (!isset($_GET['pid'])) {
    // redirect to the home page
    wp_redirect(home_url());
    exit();
}

$productInfo = TemplateProducts::getProduct(intval($_GET['pid']));
$productReviews = TemplateProducts::getReview(intval($_GET['pid']));
$images = explode(',', $productInfo->images);
// echo '<pre>';
// echo print_r($productReviews);
// echo '</pre>';
@endphp
@extends('layouts.app')
<style>
    .checked {
        color: orange;
    }

</style>
@section('content')
    <!-- items starts -->
    <div class="container mt-5">
        <!-- justify-content-center
                                                                                                                                                                                                                                                                                                                                                                                                                                                              row-cols-1 row-cols-sm-1 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 -->
        <div class="row mt-3">
            <!-- product slider -->
            <div class="col-lg-5 col-sm-12">
                <div class="item">
                    <div class="clearfix" style="max-width: 474px">
                        <ul id="image-gallery" class="gallery list-unstyled cS-hidden">
                            @foreach ($images as $img)

                                <li data-thumb={{ wp_get_attachment_image_src(intval($img))[0] }}>
                                    <img src={{ wp_get_attachment_image_src(intval($img), [250, 250])[0] }} />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- product description -->
            <div class="col-lg-7 col-sm-12 mt-4 mt-lg-0 product_desc p-2">
                <h2>{{ $productInfo->name }}</h2>
                <div class="col-12 mt-3 mb-3">
                    <span class="price new me-3 text-primary p-2 d-inline-block">{{ $productInfo->price }}</span>
                    <span class="price old text-dark p-1 d-inline-block"><del>{{ $productInfo->promo }}</del></span>
                </div>
                <!-- info form -->
                <div class="col-12 mt-2">
                    <form id="buy_form" method="POST" action={{ $_SERVER['PHP_SELF'] }}>
                        <!-- name and phone -->
                        <div class="row mt-2">
                            <div class="col">
                                <input type="text" class="form-control" name="fullName" placeholder="Full Name" />
                            </div>
                            <div class="col">
                                <input type="tel" class="form-control" name="phone" placeholder="Phone Number" />
                            </div>
                        </div>
                        <!-- country and state -->
                        <div class="row mt-2">
                            <div class="col">
                                <input type="text" class="form-control" name="country" placeholder="Country" />
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="state" placeholder="State / Region" />
                            </div>
                        </div>
                        <!-- city and address -->
                        <div class="row mt-3">
                            <div class="col">
                                <input type="text" class="form-control" name="city" placeholder="City" />
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="address" placeholder="Address" />
                            </div>
                        </div>
                        <!-- zip code -->
                        <div class="row mt-3">
                            <div class="col">
                                <input type="number" class="form-control" name="codePostal" placeholder="Code Postal" />
                                <input type="hidden" class="form-control" name="product_id"
                                    value="{{ $_GET['pid'] }}" />
                                <input type="hidden" class="form-control" name="unit_price"
                                    value="{{ $productInfo->price }}" />
                            </div>
                        </div>
                        <!-- sizes -->
                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label d-block">Sizes</label>
                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="variant" id="btnradio1"
                                        autocomplete="off" checked value="XL" />
                                    <label class="btn btn-outline-dark rounded-0" for="btnradio1">XL</label>

                                    <input type="radio" class="btn-check" name="variant" id="btnradio2"
                                        autocomplete="off" value="XXL" />
                                    <label class="btn btn-outline-dark rounded-0" for="btnradio2">XXL</label>

                                    <input type="radio" class="btn-check" name="variant" id="btnradio3"
                                        autocomplete="off" value="MD" />
                                    <label class="btn btn-outline-dark rounded-0" for="btnradio3">MD</label>
                                </div>
                            </div>
                        </div>
                        <!-- colors -->
                        <div class="row mt-3">
                            <div class="col">
                                <label class="form-label d-block">Colors</label>
                                <select name="colors" class="form-select form-select-sm">
                                    <option value="1" selected>color one</option>
                                    <option value="2">color two</option>
                                    <option value="3">color tree</option>
                                </select>
                            </div>
                        </div>

                        <!-- qte -->
                        <div class="row mt-3">
                            <div class="col-4">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <button id="btn_sub_qte" class="btn btn-secondary border-0 rounded-0" type="button">
                                            -
                                        </button>
                                    </div>
                                    <input type="number" min="1" id="order_qte_input" name="order_qte"
                                        class="form-control text-center" value="1" />
                                    <div class="input-group-append">
                                        <button id="btn_add_qte" class="btn btn-secondary rounded-0 border-0" type="button">
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">
                                <button type="submit" name="btn_add_new_order" value="add_new_order"
                                    class="btn_buy_now btn btn-primary rounded-0 w-100 border-0">
                                    اشتري الان
                                </button>
                            </div>
                        </div>
                        <!-- flip count down -->
                    </form>
                </div>
                <!-- count doun -->
                <div class="row">
                    <div class="col-12 text-center mt-4">
                        <h2 class="countdown_title">Hurry up to buy, limited quantity</h2>
                        <div id="flipdown" class="flipdown"></div>
                    </div>
                </div>
                <!-- more description -->
                <div class="row justify-content-center mt-5">
                    <div class="col-12 text-center">
                        {{-- <h2>الحزام الاكثر مبيعا في المغرب لسنة 2021</h2>
                        <p>
                            اكتشف Comfy Slim الحزام الحصري الجديد بحافة مقواة وتقنية Slim
                            Flex المثالية التي تساعد على ضغط جسمك و تشكيل قوامك. بالاضافة
                            الى النتائج الفعالة, سوف يوفر لك Comfy Slim الراحة و التنقل مع
                            تعديل الحجم القابل للتعديل, لذلك يمكنك تحديد مستوى الضغط. انها
                            مصنوعة من مواد عالية الجودة, خاصة لراحتك و ثقتك بنفسك.
                        </p>
                        <p>
                            اكتشف Comfy Slim الحزام الحصري الجديد بحافة مقواة وتقنية Slim
                            Flex المثالية التي تساعد على ضغط جسمك و تشكيل قوامك. بالاضافة
                            الى النتائج الفعالة, سوف يوفر لك Comfy Slim الراحة و التنقل مع
                            تعديل الحجم القابل للتعديل, لذلك يمكنك تحديد مستوى الضغط. انها
                            مصنوعة من مواد عالية الجودة, خاصة لراحتك و ثقتك بنفسك.
                        </p>
                        <p>
                            اكتشف Comfy Slim الحزام الحصري الجديد بحافة مقواة وتقنية Slim
                            Flex المثالية التي تساعد على ضغط جسمك و تشكيل قوامك. بالاضافة
                            الى النتائج الفعالة, سوف يوفر لك Comfy Slim الراحة و التنقل مع
                            تعديل الحجم القابل للتعديل, لذلك يمكنك تحديد مستوى الضغط. انها
                            مصنوعة من مواد عالية الجودة, خاصة لراحتك و ثقتك بنفسك.
                        </p>
                        <p>
                            اكتشف Comfy Slim الحزام الحصري الجديد بحافة مقواة وتقنية Slim
                            Flex المثالية التي تساعد على ضغط جسمك و تشكيل قوامك. بالاضافة
                            الى النتائج الفعالة, سوف يوفر لك Comfy Slim الراحة و التنقل مع
                            تعديل الحجم القابل للتعديل, لذلك يمكنك تحديد مستوى الضغط. انها
                            مصنوعة من مواد عالية الجودة, خاصة لراحتك و ثقتك بنفسك.
                        </p> --}}
                        {{ $productInfo->description }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <!-- product reviews list -->
            @include('partials.product.reviews')
        </div>
    </div>
    <!-- sticky by form -->
    <div
        class="
        container-fluid
        border-top
        fixed-bottom
        bg-white
        p-0
        sticky_by_form
      ">
        <div class="row justify-content-center">
            <div class="col-6">
                <!-- submit btn -->
                <div class="row mt-3">
                    <div class="col">
                        <button id="scrolltofrom" type="submit"
                            class="btn_buy_now btn btn-primary rounded-0 w-100 border-0">
                            اشتري الان
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Unix timestamp (in seconds) to count down to
        var twoDaysFromNow = (new Date().getTime() / 1000) + (86400 * 2) + 1;

        // Set up FlipDown
        var flipdown = new FlipDown(twoDaysFromNow)

            // Start the countdown
            .start()

            // Do something when the countdown ends
            .ifEnded(() => {
                console.log('The countdown has ended!');
            });

        // Toggle theme
        // var interval = setInterval(() => {
        //     let body = document.body;
        //     body.classList.toggle('light-theme');
        //     body.querySelector('#flipdown').classList.toggle('flipdown__theme-dark');
        //     body.querySelector('#flipdown').classList.toggle('flipdown__theme-light');
        // }, 5000);

        // Show version number
        // var ver = document.getElementById('ver');
        // ver.innerHTML = flipdown.version;
    });
</script>
