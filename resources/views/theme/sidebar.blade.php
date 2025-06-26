<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link " href="/dashboard" >
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <a class="nav-link" href="/customer">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Customers
                </a>
                <a class="nav-link" href="/site">
                    <div class="sb-nav-link-icon"><i class="fas fa-sitemap"></i></div>
                    Sites
                </a>
                <a class="nav-link" href="/installation">
                    <div class="sb-nav-link-icon"><i class="fas fa-server"></i></div>
                    CHP Installations
                </a>
                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link" href="/users">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Users
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in user:</div>
            {{ auth()->user()->name }}
        </div>
    </nav>
</div>