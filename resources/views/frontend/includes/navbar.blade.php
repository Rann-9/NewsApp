<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>ZenBlog</h1>
      </a>

      <nav id="navbar" class="navbar">
        @foreach ($category as $row)
        <ul>
          <li><a href="#">{{ $row->name }}</a></li>
        </ul>
        @endforeach
      </nav><!-- .navbar -->

      <div class="position-relative">
        <a href="#" class="mx-2"><span class="bi-facebook"></span></a>
        <a href="#" class="mx-2"><span class="bi-twitter"></span></a>
        <a href="#" class="mx-2"><span class="bi-instagram"></span></a>

        <a href="#" class="mx-2 js-search-open"><span class="bi-search"></span></a>
        <i class="bi bi-list mobile-nav-toggle"></i>

        <!-- ======= Search Form ======= -->
        <div class="search-form-wrap js-search-form-wrap">
          <form action="search-result.html" class="search-form">
            <span class="icon bi-search"></span>
            <input type="text" placeholder="Search" class="form-control">
            <button class="btn js-search-close"><span class="bi-x"></span></button>
          </form>
        </div><!-- End Search Form -->

        {{-- bila belum login, maka akan muncul login dan register. Jika sudah login maka akan muncul nama home --}}
        @guest
            <a href="{{ route('login') }}" class="mx-2">Login</a>
            <a href="{{ route('register') }}" class="mx-2">Register</a>
        @else
        <a href="{{ route('home') }}" class="mx-2">Home</a>
        @endguest

      </div>

    </div>

  </header>