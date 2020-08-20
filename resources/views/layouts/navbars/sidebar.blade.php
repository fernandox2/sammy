<nav class="navbar navbar-horizontal fixed-left navbar-expand-md navbar-light" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('argon') }}/img/brand/blue.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('¡ Bienvenido !') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Mi Perfil') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Opciones') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Auditorias') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Soporte') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Cerrar Sesión') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/blue.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Form -->
            <form class="mt-4 mb-3 d-md-none">
                <div class="input-group input-group-rounded input-group-merge">
                    <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="{{ __('Search') }}" aria-label="Search">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-search"></span>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Navigation -->
            <ul class="navbar-nav">
                <li @click.prevent="menu=0" class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="ni ni-tv-2 text-default"></i> {{ __('Panel') }}
                    </a>
                </li>

                <li @click.prevent="menu=1" class="nav-item">
                    <a class="nav-link" href="#">
                    <i class="ni ni-money-coins text-default"></i> {{ __('Cotizador') }}
                    </a>
                </li>
                <li @click.prevent="menu=7" class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-bus-front-12 text-default"></i> {{ __('Vehiculos') }}
                    </a>
                </li>
                <li @click.prevent="menu=6" class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="ni ni-bus-front-12 text-default"></i> {{ __('Tipo de Vehículos') }}
                    </a>
                </li>
                    @if(Auth::user()->rol == 'Supervisor' || Auth::user()->rol == 'Administrador')

                    <li class="nav-item dropdown">
                            
                            <a class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ni ni-bag-17 text-default"></i> {{ __('Mantenedores') }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a @click.prevent="menu=5" class="ni ni-single-02 dropdown-item text-default" href="#">  USUARIOS</a>
                                <a @click.prevent="menu=5" class="ni ni-delivery-fast dropdown-item text-default" href="#">  TIPOS DE VEHICULOS</a>
                                <a @click.prevent="menu=5" class="ni ni-satisfied dropdown-item text-default" href="#">  CLIENTES</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">  OPCIONES</a>
                            </div>
                    </li>
                    @endif
                    @if(Auth::user()->rol == 'Administrador')
                        <li @click.prevent="menu=3" class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="ni ni-building text-default"></i> {{ __('Empresas') }}
                            </a>
                        </li>
                    @endif

                    <li @click.prevent="menu=4" class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ni ni-chart-bar-32 text-default"></i>{{ __('Informes') }}
                        </a>
                    </li>

            </ul>
            <!-- Divider -->
            <hr class="my-3">
            <!-- Heading -->

        </div>
    </div>
</nav>