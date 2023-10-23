@inject('request', 'Illuminate\Http\Request')
@if (Auth::user() == null)
    {!! redirect()->route('home') !!}
@endif
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-default elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/admin/home') }}" class="brand-link" style="text-align: center;">
        <img src="{{ url('/Logo_Presidencia_traslucido_v2.png') }}" class="" style="opacity: .8;width:100px">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex hidden">
            <div class="image">
                <img src="{{ asset('/img/avatar_plusis.png') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <span> {{ Auth::user()->name }}</span>
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar hidden" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append hidden">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @auth
                    @if (Auth::user()->evaluarole(['SERVIDOR']))
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>
                                    CONECTITY
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ url('storage/') }}/conectity_w10.exe" class="nav-link " download>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>WINDOWS 10</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('storage/') }}/conectity_w7.exe" class="nav-link " download>
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>WINDOWS 7</p>
                                    </a>
                                </li>
                            </ul>
                    @endif
                @endauth
                @foreach ($menus as $key => $item)
                    @if ($item['parent'] != 0)
                    @break
                @endif
                @include('partials.menu-item', ['item' => $item, 'varta' => 1])
            @endforeach
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
