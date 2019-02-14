<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
  </head>
  <body>
    <h1>Selamat Datang di Laravel Framework</h1>

    <header>
      <nav>
        <li><a href="">Home</a></li>
        <li><a href="penulis">Penulis</a></li>
        <li><a href="penerbit">Penerbit</a></li>
        <li><a href="buku">Buku</a></li>
        <li><a href="blog">Blog</a></li>
      </nav>
    </header>
    <br>
    @yield('content')


    <br>
    <footer>
&copy; Laravel {{date('Y')}}
    </footer>
  </body>
</html>
