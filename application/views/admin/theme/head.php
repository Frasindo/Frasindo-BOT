    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>{title}</title>
    		{css}
        <link rel="stylesheet" href="{url}">
    		{/css}
    </head>

    <body class="skin-default-dark fixed-layout">
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">System Is Loading . . </p>
            </div>
        </div>
        <style media="screen">
          /* .modal-dialog{
            overflow-y:scroll;
          } */
          .modal-dialog{
            max-width: 100%;
            width:80%;
          }
        </style>
        <?php
        $base = function($page = ''){
          return base_url("admin/".$page);
        }
        ?>
        <div id="main-wrapper">
            <header class="topbar">
                <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                    <div class="navbar-collapse">
                    </div>
                </nav>
            </header>
            <aside class="left-sidebar">
                <div class="d-flex no-block nav-text-box align-items-center">
                    <!-- <span><img src="<?= base_url("assets/extra/log.png") ?>" alt="Logo Bandung"></span> -->
                    <a class="waves-effect waves-dark ml-auto hidden-sm-down" href="javascript:void(0)"><i class="ti-menu"></i></a>
                    <a class="nav-toggler waves-effect waves-dark ml-auto hidden-sm-up" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <div class="scroll-sidebar">
                    <nav class="sidebar-nav">
                        <ul id="sidebarnav">
                            <li> <a class="waves-effect waves-dark" href="<?= $base() ?>" aria-expanded="false"><i class="fa fa-tachometer"></i><span class="hide-menu">Dashboard</span></a></li>
                            <li> <a class="waves-effect waves-dark" href="<?= $base("ardoraccount") ?>" aria-expanded="false"><i class="fa fa-list"></i><span class="hide-menu">Ardor Account</span></a></li>
                            <li> <a class="waves-effect waves-dark" href="<?= $base("report") ?>" aria-expanded="false"><i class="fa fa-globe"></i><span class="hide-menu">Report Bot</span></a></li>
                            <li> <a class="waves-effect waves-dark" href="<?= $base("botrules") ?>" aria-expanded="false"><i class="fa fa-heart"></i><span class="hide-menu">Bot Rules</span></a></li>
                            <li> <a class="waves-effect waves-dark" href="<?= $base("logfailed") ?>" aria-expanded="false"><i class="fa fa-search"></i><span class="hide-menu">Log Failed</span></a></li>
                            <li> <a class="waves-effect waves-dark" href="<?= $base("adminmanage") ?>" aria-expanded="false"><i class="fa fa-arrow-left"></i><span class="hide-menu">Admin Management</span></a></li>
                            <li> <a class="waves-effect waves-dark" href="<?= base_url("logout") ?>" aria-expanded="false"><i class="fa fa-sign-out"></i><span class="hide-menu">Logout</span></a></li>
                        </ul>
                    </nav>
                </div>
            </aside>
