 
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('../assets/img/apple-icon.png') }} ">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   {{ $title }}
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet">
</head>

<body class="g-sidenav-show  bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  @include('layouts.sidebar')
  <main class="main-content position-relative border-radius-lg ">
    @yield('content')
  </main>
  @include('layouts.js')
</body>

</html>