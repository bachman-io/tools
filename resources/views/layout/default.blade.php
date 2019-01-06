<html class="no-js" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
    <head>
        <title>{{$title}}</title>
        <link rel='dns-prefetch' href='//cdnjs.cloudflare.com' />
        <link rel='dns-prefetch' href='//static.bachman.io' />
        <link rel='dns-prefetch' href='//use.fontawesome.com' />
        <link rel='dns-prefetch' href='//s.w.org' />

        <link rel='stylesheet' id='bootstrap-css'  href='https://static.bachman.io/web/assets/v2/css/style.min.css?ver=4.1.3' type='text/css' media='all' />
        <link rel='stylesheet' id='fontawesome5-css'  href='https://use.fontawesome.com/releases/v5.4.1/css/all.css?ver=5.4.1' type='text/css' media='all' />
    </head>

<body>
<nav id="navbar" class="navbar navbar-expand-md navbar-dark fixed-top" style="background: #333;">
    <div class="container" style="border-bottom: 1px solid #dddddd;">

        <a href="https://www.bachman.io/"><img
                    src="https://static.bachman.io/web/assets/v2/svg/bachman-white.svg"
                    style="width: auto; height: 30px;"
                    alt="Bachman I/O" /></a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDropdown" aria-controls="navbarDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarDropdown">
            <ul id="menu-main-menu" class="navbar-nav mr-auto align-items-end " style="min-height: 55px;">
                <li  id="menu-item-37" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home active nav-item nav-item-37"><a href="https://www.bachman.io/" class="nav-link">Home</a></li>

                <li  id="menu-item-12" class="menu-item menu-item-type-post_type menu-item-object-page nav-item nav-item-12"><a href="https://www.bachman.io/blog/" class="nav-link">Blog</a></li>

                <li  id="menu-item-12" class="menu-item menu-item-type-post_type menu-item-object-page nav-item nav-item-12"><a href="https://tools.bachman.io/" class="nav-link">Tools</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1 class="display-1 text-center">Tools</h1>
    @include('layout.navbar')
    @yield('content')
</div>

<div class="container">
    <hr />
    <div class="row pt-3">
        <div class="col-sm">
            <p class="text-center text-sm-left">&copy; 2019 <a href="https://www.bachman.io/">Collin Bachman</a> | <a href="/privacy">Privacy</a></p>
        </div>
        <div class="col-sm">
            <p class="text-center text-sm-right">Site design adapted from the <a href="https://github.com/SimonPadbury/b4st">b4st</a> theme for WordPress</p>
        </div>
    </div>
</div>

<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js?ver=2.8.3'></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js?ver=1.14.3'></script>
<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js?ver=4.1.3'></script>
</body>
</html>