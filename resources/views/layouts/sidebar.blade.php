<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="../pages/dashboard.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="{{ route('admin.levels.index') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manajemen Level</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="{{ route('admin.matapelajaran.index') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manajemen Matapelajaran</span>
          </a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link " href="{{ route('admin.soal.index') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-app text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Manajemen Soal</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="{{ route('admin.hasilpembelajaran.index') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Hasil Pembelajaran</span>
          </a>
        </li>
       
        @if (Auth::user()->role == 'super_admin')
        
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Tambah Akun Admin</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('super_admin.registration_admin') }}">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Registrasi Admin</span>
          </a>
        </li>
        @endif
        
        <li class="nav-item">
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link btn btn-link" style="display: flex; align-items: center; width: 100%; text-align: left;">
              <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-button-power text-dark text-sm opacity-10"></i>
              </div>
              <span class="nav-link-text ms-1">Logout</span>
            </button>
          </form>
        </li>
        
      </ul>
    </div>
    
  </aside>

  