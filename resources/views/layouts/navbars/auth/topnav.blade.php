<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg p-0 shadow-none border-radius-xl
        {{ str_contains(Request::url(), 'virtual-reality') == true ? ' mt-3 mx-3 bg-primary' : '' }}"
    id="navbarBlur" data-scroll="false">
    <div class="container-fluid py-1 px-1">
        <div class="d-flex justify-content-start">
            <img src="{{ asset('img/logo-bisi.png') }}" width="50" alt="Logo" class="img img-thumbnail">

            <span class="text-white font-weight-bolder mt-3 ms-2">PT. BISI International, Tbk.</span>

        </div>
        <nav aria-label="breadcrumb">
            <h5 class="font-weight-bolder text-white position-absolute start-50 translate-middle">
                {{ $title }}</h5>
        </nav>

        <div class="collapse navbar-collapse d-flex me-5 justify-content-end" id="navbar">
            <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    {{ session('fullname') }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">

                    <li><a class="dropdown-item" href="{{ url('/') }}">
                            <i class="fa fa-folder"></i> Report</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('monitoring') }}">
                            <i class="fa fa-tv"></i> Monitoring</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('pcs.get') }}">
                            <i class="fa fa-circle"></i> Product Pcs</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('box.get') }}">
                            <i class="fa fa-box"></i> Product Box</a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('batch_production.get') }}">
                            <i class="fa fa-gear"></i> Batch Production</a>
                    </li>

                    <li>
                        <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="nav-link px-0">
                                <i class="fa fa-power-off"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

    </div>
</nav>
<!-- End Navbar -->
