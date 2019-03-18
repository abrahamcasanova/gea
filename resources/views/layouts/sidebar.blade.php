<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="navbar-nav">

            <li>
                <a class="nav-link" href="{{url('/dashboard')}}">
                    <i class="nav-icon icon-speedometer"></i> Dashboard
                </a>
            </li>
            @can('read-users','read-roles')
                <li class="nav-title">{{ __('Settings') }}</li>
            @endcan
            @can('read-users')
                <li class="nav-item">
                    <a class="nav-link " href="{{url('/users')}}">
                        <i class="nav-icon icon-people"></i> {{ __('Users') }}
                    </a>
                </li>
            @endcan
            @can('read-catalogs')
                    <div class="nav-item dropdown" href="#">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="nav-icon icon-folder"></i> {{ __('Catalogs') }}</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                            <a class="dropdown-item" href="{{url('/products')}}"> <i class="nav-icon currentColor icon-bag"></i> {{ __('Products') }} </a>
                            <a class="dropdown-item" href="{{url('/products_type')}}"> <i class="nav-icon currentColor icon-bag"></i> {{ __('ProductType') }} </a>
                            <a class="dropdown-item" href="{{url('/suppliers')}}"> <i class="nav-icon currentColor icon-people"></i> {{ __('Providers') }} </a>
                            @can('read-prospectings')
                                <a style="display:none;" class="dropdown-item" href="{{url('/prospectings')}}"> <i class="nav-icon currentColor icon-user"></i> {{ __('Prospectings') }} </a>
                            @endcan
                            @can('read-customers')
                                <a class="dropdown-item" href="{{url('/customers')}}">
                                    <i class="nav-icon icon-user-following currentColor"></i> {{ __('CustomersProspecting') }}
                                </a>
                            @endcan
                        </div>
                    </div>
            @endcan
            @can('read-customer-orders')
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/customers_orders')}}">
                        <i class="nav-icon icon-folder"></i> Solicitudes de cotizaciones
                    </a>
                </li>
            @endcan
            @can('read-customer-orders')
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/quotes')}}">
                        <i class="nav-icon icon-folder"></i> Cotizaciones
                    </a>
                </li>
            @endcan
            @can('read-sales')
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/sales')}}">
                        <i class="nav-icon far fa-check-circle"></i> {{ __('Sales') }}
                    </a>
                </li>
            @endcan
            @can('read-payments')
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/payments')}}">
                        <i class="nav-icon icon-wallet"></i> {{ __('Payments') }}
                    </a>
                </li>
            @endcan
            @can('read-reports')
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/reports')}}">
                        <i class="nav-icon icon-graph"></i> {{ __('Reports') }}
                    </a>
                </li>
            @endcan
            @can('read-roles')
                <li class="nav-item dropdown">
                	<a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown">  <i class="nav-icon icon-key"></i> {{ __('Roles Permissions') }}  </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{url('/roles')}}"> <i class="nav-icon currentColor icon-key"></i> {{ __('Roles') }} </a></li>
                	      <li><a class="dropdown-item" href="{{url('/permissions')}}"> <i class="nav-icon currentColor icon-key"></i> {{ __('Permissions') }}  </a></li>
                    </ul>
                </li>
            @endcan
        </ul>
    </nav>
    <sidebar></sidebar>
</div>
