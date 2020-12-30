<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema OCS Laravel Vue Js">
    <meta name="author" content="Fabricio Riascos">
    <meta name="keyword" content="Sistema OCS Laravel Vue Js">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="img/logo.png">
    <title>Sistema OCS</title>

    <!-- Icons -->
    <!-- Main styles for this application -->
    <link href="css/plantilla.css" rel="stylesheet">
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
            <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="img/logo.png"></a>
            <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
            <span class="navbar-toggler-icon"></span>
            </button>
            
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="img/avatar.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        <span class="d-md-down-none">admin </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>Cuenta</strong>
                        </div>
                        <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Perfil</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-lock"></i> Cerrar sesión</a>
                    </div>
                </li>
            </ul>
        </header>

        <div class="app-body">
            @include('plantilla.sidebar')
            <!-- Contenido Principal -->
            @yield('contenido')
            <!-- /Fin del contenido principal -->
        </div>
    </div>
    

    <footer class="app-footer">
        <span>Riascos Fabricio &copy; 2020</span>
        <span class="ml-auto">Desarrollado por Riascos Fabricio</span>
    </footer>

    <!-- Bootstrap and necessary plugins -->
    <!-- Plugins and scripts required by all views -->
    <!-- GenesisUI main scripts -->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
</body>

</html>