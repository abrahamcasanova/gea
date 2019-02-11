<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="navbar-nav">

            <li>
                <a class="nav-link" href="/dashboard">
                    <i class="nav-icon icon-speedometer"></i> Dashboard
                </a>
            </li>
            @can('read-users','read-roles')
                <li class="nav-title">{{ __('Settings') }}</li>
            @endcan
            @can('read-users')
                <li class="nav-item">
                    <a class="nav-link " href="/users">
                        <i class="nav-icon icon-people"></i> {{ __('Users') }}
                    </a>
                </li>
            @endcan
            @can('read-catalogs')
                    <div class="nav-item dropdown" href="#">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="nav-icon icon-folder"></i> {{ __('Catalogs') }}</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                            <a class="dropdown-item" href="/products"> <i class="nav-icon currentColor icon-bag"></i> {{ __('Products') }} </a>
                            <a class="dropdown-item" href="/products_type"> <i class="nav-icon currentColor icon-bag"></i> {{ __('ProductType') }} </a>
                            <a class="dropdown-item" href="/suppliers"> <i class="nav-icon currentColor icon-people"></i> {{ __('Providers') }} </a>
                            @can('read-prospectings')
                                <a class="dropdown-item" href="/prospectings"> <i class="nav-icon currentColor icon-user"></i> {{ __('Prospectings') }} </a>
                            @endcan
                            @can('read-customers')
                                <a class="dropdown-item" href="/customers">
                                    <i class="nav-icon icon-user-following currentColor"></i> {{ __('Customers') }}
                                </a>
                            @endcan
                        </div>
                    </div>
            @endcan
            @can('read-sales')
                <li class="nav-item">
                    <a class="nav-link" href="/sales">
                        <i class="nav-icon far fa-check-circle"></i> {{ __('Sales') }}
                    </a>
                </li>
            @endcan
            @can('read-payments')
                <li class="nav-item">
                    <a class="nav-link" href="/payments">
                        <i class="nav-icon icon-wallet"></i> {{ __('Payments') }}
                    </a>
                </li>
            @endcan
            @can('read-reports')
                <li class="nav-item">
                    <a class="nav-link" href="/reports">
                        <i class="nav-icon icon-graph"></i> {{ __('Reports') }}
                    </a>
                </li>
            @endcan
            @can('read-roles')
                <li class="nav-item dropdown">
                	<a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown">  <i class="nav-icon icon-key"></i> {{ __('Roles Permissions') }}  </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/roles"> <i class="nav-icon currentColor icon-key"></i> {{ __('Roles') }} </a></li>
                	      <li><a class="dropdown-item" href="/permissions"> <i class="nav-icon currentColor icon-key"></i> {{ __('Permissions') }}  </a></li>
                    </ul>
                </li>
            @endcan
        </ul>
    </nav>
    <sidebar></sidebar>
</div>
