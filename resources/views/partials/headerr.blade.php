@php
$menuName = wp_get_theme()->name . ' Header Menu';
$menu = wp_get_nav_menu_object($menuName);
$menu_items = wp_get_nav_menu_items($menu);

// $current_url =  home_url( $wp->request );
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
@endphp
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
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
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
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
        <div class="d-none d-lg-block" style="cursor: pointer;">
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

    </nav>
</div>
