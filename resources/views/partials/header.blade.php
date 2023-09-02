@php
$menuName = wp_get_theme()->name . ' Header Menu';
$menu = wp_get_nav_menu_object($menuName);
$menu_items = wp_get_nav_menu_items($menu);

// $current_url =  home_url( $wp->request );
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
@endphp
<div class="container">

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <div class="d-flex">
                <div class="me-4 d-lg-none">
                    <div class="bg-light p-2">
                        <button class="border-0 bg-light rounded text-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-cart" viewBox="0 0 16 16">
                                <path
                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                                </path>
                            </svg>
                        </button>
                        <span class="text-uppercase">57020 MAD</span>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    @foreach ($menu_items as $item)
                        <li class="nav-item {{ $current_url == $item->url ? 'active' : '' }}">
                            <a class="nav-link {{ $current_url == $item->url ? 'active' : '' }}"
                                href="{{ $item->url }}">{{ $item->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="d-none d-lg-block" id="btn_open_desk_shopping_cart" style="cursor: pointer;">
                <div class="bg-light p-2">
                    <button class="border-0 bg-light rounded text-center align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-cart" viewBox="0 0 16 16">
                            <path
                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                            </path>
                        </svg>
                    </button>
                    <span class="text-uppercase">50000 MAD</span>
                </div>
            </div>
        </div>
    </nav>

    {{-- large screen cart --}}
    <div class="desktop_shpping_cart overflow-scroll pb-5 vh-100 bg-white position-fixed top-0 end-0 opacity-0" style="z-index: 2000;width:30%;">
      {{-- shopping cart header --}}
      <div class="d-flex justify-content-between p-2 mt-2">
        <button class="btn btn-transparent" id="btn_close_desk_shopping_cart">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
            <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
          </svg>
        </button>
        <span class="d-inline-block p-2 border">21554 MAD</span>
      </div>
      {{-- shopping card items --}}
      <div class="p-2">
        <div class="card rounded-0" style="width: 100%;">
          {{-- item --}}
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-4">
                <img src="https://image.uniqlo.com/UQ/ST3/AsianCommon/imagesgoods/422990/sub/goods_422990_sub13.jpg?width=100&impolicy=quality_75" width="100" />
              </div>
              <div class="col-6">
                <h6 class="mb-3 text-capitalize">t shirt name goes here <span>- 100 MAD</span></h6>
                <div class="input-group mb-3" style="width:120px;">
                  <div class="input-group-prepend">
                      <button class="shop_btn_sub_qte btn btn-secondary border-0 rounded-0" type="button">
                          -
                      </button>
                  </div>
                  <input type="text" min="1" id="shop_order_qte_input" name="order_qte" class="form-control text-center" value="1">
                  <div class="input-group-append">
                      <button class="shop_btn_add_qte btn btn-secondary rounded-0 border-0" type="button">
                          +
                      </button>
                  </div>
              </div>
              </div>
              <div class="col-2">
                <button class="btn btn-transparent">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                    <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-4">
                <img src="https://image.uniqlo.com/UQ/ST3/AsianCommon/imagesgoods/422990/sub/goods_422990_sub13.jpg?width=100&impolicy=quality_75" width="100" />
              </div>
              <div class="col-6">
                <h6 class="mb-3 text-capitalize">t shirt name goes here <span>- 100 MAD</span></h6>
                <div class="input-group mb-3" style="width:120px;">
                  <div class="input-group-prepend">
                      <button class="shop_btn_sub_qte btn btn-secondary border-0 rounded-0" type="button">
                          -
                      </button>
                  </div>
                  <input type="text" min="1" id="shop_order_qte_input" name="order_qte" class="form-control text-center" value="1">
                  <div class="input-group-append">
                      <button class="shop_btn_add_qte btn btn-secondary rounded-0 border-0" type="button">
                          +
                      </button>
                  </div>
              </div>
              </div>
              <div class="col-2">
                <button class="btn btn-transparent">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                    <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-4">
                <img src="https://image.uniqlo.com/UQ/ST3/AsianCommon/imagesgoods/422990/sub/goods_422990_sub13.jpg?width=100&impolicy=quality_75" width="100" />
              </div>
              <div class="col-6">
                <h6 class="mb-3 text-capitalize">t shirt name goes here <span>- 100 MAD</span></h6>
                <div class="input-group mb-3" style="width:120px;">
                  <div class="input-group-prepend">
                      <button class="shop_btn_sub_qte btn btn-secondary border-0 rounded-0" type="button">
                          -
                      </button>
                  </div>
                  <input type="text" min="1" id="shop_order_qte_input" name="order_qte" class="form-control text-center" value="1">
                  <div class="input-group-append">
                      <button class="shop_btn_add_qte btn btn-secondary rounded-0 border-0" type="button">
                          +
                      </button>
                  </div>
              </div>
              </div>
              <div class="col-2">
                <button class="btn btn-transparent">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                    <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


</div>
