<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}"/>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aquamazonia') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="{{ asset('css/fonts-google.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('css/solid.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light border-bottom shadow-sm border" style="background-color: #e3f2fd;">
            <div class="container">
               <div class="text-center">
                 	<img id="logo-app" src="{{ asset('img/logo-aquamazonia.jpg') }}" alt="Logo-aquamazonia" style="width:70px; border-radius:50%">
                    <br>
                    <h2 id="title-app-shadow">{{ config('app.name', 'Aquamazonia') }}</h2> 
               </div>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav row" id="main-navigation">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item ">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Ingresar') }}</a>
                            </li>
                            @if (Route::has('register'))
                            
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item col-nav-item ">
                                <router-link class="nav-link active" to="/dashboard">Panel principal</router-link>
                            </li>
                            <li class="nav-item col-nav-item ">
                                <router-link class="nav-link" to="/siembras">Siembras</router-link>
                            </li>
                           
                            <li class="nav-item col-nav-item ">
                                <router-link class="nav-link" to="/recursos-necesarios">Recursos necesarios</router-link>
                            </li>
                            <li class="nav-item col-nav-item ">
                                <router-link class="nav-link" to="/alimentacion">Alimentación</router-link>
                            </li>
                            <li class="nav-item col-nav-item ">
                                <router-link class="nav-link" to="/informes-biomasa-alimento">Biomasa por Alimentación</router-link>
                            </li>
                            <li class="nav-item col-nav-item ">
                                <router-link class="nav-link" to="/informes-produccion">Informes Producción</router-link>
                            </li>
                            <li class="nav-item col-nav-item ">
                                <router-link class="nav-link" to="/calidad-agua">Registrar parametros de calidad  del Agua</router-link>
                            </li>
                            <!-- <li class="nav-item col-nav-item ">
                                <router-link class="nav-link" to="/informes">Informes</router-link>
                            </li> -->
                            <li class="nav-item col-nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                   Informes<span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <router-link class="dropdown-item" to="/informe-consolidado">Informe consolidado variables de producción </router-link>                            
                                    <router-link class="dropdown-item" to="/informes">Informe recursos </router-link>          
                                    <router-link class="dropdown-item" to="/informes-recursos-necesarios">Informe consolidado recursos necesarios</router-link>
                                    <router-link class="dropdown-item" to="/informes-parametros">Informe Párametros de calidad del agua </router-link>                            
                                    <!-- <router-link class="dropdown-item" to="/informe-siembras">Informe siembras</router-link>       -->
                                    <router-link class="dropdown-item" to="/ciclo-productivo">Informe ciclo productivo</router-link>      
                                    <router-link class="dropdown-item" to="/informe-actividades">Informe muestreo y pesca</router-link>      
                                    <!-- <router-link class="dropdown-item" to="/calidad-agua">Informe Calidad Agua</router-link>       -->
                                </div>
                            </li>
                            <li class="nav-item col-nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                   Configuracion<span class="caret"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <router-link class="dropdown-item" to="/contenedores">Contenedores</router-link>
                                    <router-link class="dropdown-item" to="/especies">Especies</router-link>
                                    <router-link class="dropdown-item" to="/alimentos">Alimentos</router-link>
                                    <router-link class="dropdown-item" to="/recursos">Recursos</router-link>                                
                                    <router-link class="dropdown-item" to="/usuarios">Usuarios</router-link>
                                </div>
                            </li>
                            <li class="nav-item col-nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
																						document.getElementById('logout-form').submit();">
                                        {{ __('Salir') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer class="text-primary text-center">
            <small>
                Desarrollado por <b>FRACTAL AGENCIA DIGITAL 💙</b>
            </small>
        </footer>
    </div>
</body>
</html>
