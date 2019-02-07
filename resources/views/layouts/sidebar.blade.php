<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="/dashboard">
                    <i class="nav-icon icon-speedometer"></i> Dashboard
                </a>
            </li>
            @can('read-users','read-roles')
                <li class="nav-title">{{ __('Settings') }}</li>
            @endcan
            @can('read-users')
                <li class="nav-item">
                    <a class="nav-link" href="/users">
                        <i class="nav-icon icon-people"></i> {{ __('Users') }}
                    </a>
                </li>
            @endcan
            @can('read-customers')
                <li class="nav-item">
                    <a class="nav-link" href="/customers">
                        <i class="nav-icon icon-people"></i> {{ __('Customers') }}
                    </a>
                </li>
            @endcan
            @can('read-prospectings')
                <li class="nav-item dropdown">
                  <a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown">  <i class="nav-icon icon-people"></i> {{ __('Prospectings') }}   </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/prospectings"> <i class="nav-icon currentColor icon-people"></i> {{ __('New Prospecting') }} </a></li>
                        <li><a class="dropdown-item" href="/prospectings/track-prospecting"> <i class="nav-icon currentColor icon-target"></i> {{ __('New Track') }}  </a></li>
                    </ul>
                </li>
            @endcan
            @can('read-catalogs')
                <li class="nav-item dropdown">
                	<a class="nav-link  dropdown-toggle" href="#" data-toggle="dropdown">  <i class="nav-icon icon-folder"></i> {{ __('Catalogs') }}   </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/agents"> <i class="nav-icon currentColor icon-people"></i> {{ __('Providers') }} </a></li>
                    </ul>
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
