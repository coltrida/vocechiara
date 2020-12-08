<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Vocechiara</title>

    <!-- Custom fonts for this template-->

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Page level plugin CSS-->
    <link href="{{asset('datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin.min.css')}}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body id="page-top">

<nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="{{route('index')}}">Vocechiara</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Right Side Of Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        @guest
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
            @endif
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest
    </ul>

</nav>
@auth
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="sidebar navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="{{route('home')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="pagesClienti" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Clienti</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="pagesClienti">
                    <h6 class="dropdown-header">Main:</h6>
                    <a class="dropdown-item" href="{{route('clients.index')}}">Lista</a>
                    <a class="dropdown-item" href="{{route('prove.index')}}">Prove in corso</a>
                    <a class="dropdown-item" href="{{route('clients.create')}}">Nuovo Cliente</a>
                    {{--<a class="dropdown-item" href="{{route('clients.find')}}">Ricerca</a>--}}
                    {{--<a class="dropdown-item" href="register.html">Register</a>
                    <a class="dropdown-item" href="forgot-password.html">Forgot Password</a>--}}
                    @if(!auth()->user()->isAudio())
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Other Functions:</h6>
                        <a class="dropdown-item" href="#">importa</a>
                        <a class="dropdown-item" href="#">Esporta</a>
                        {{--<a class="dropdown-item" href="blank.html">Blank Page</a>--}}
                    @endif
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="pagesMagazzino" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Magazzino</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="pagesMagazzino">
                    <h6 class="dropdown-header">Magazzino:</h6>
                    <a class="dropdown-item" href="{{route('products.index')}}">Lista</a>
                    @if(auth()->user()->isAudio())
                        <a class="dropdown-item" href="{{route('products.richiedi')}}">Richiedi prodotti</a>
                    @endif
                    @if(!auth()->user()->isAudio())
                        <a class="dropdown-item" href="{{route('products.create')}}">Nuovo Prodotto</a>
                        <div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Altri Magazzini:</h6>
                        @foreach($filiali as $filiale)
                            <a class="dropdown-item" href="{{route('products.altroMagazzino', $filiale->id)}}">{{$filiale->nome}}</a>
                        @endforeach

                    @endif
                </div>
            </li>
            @if(!auth()->user()->isAudio())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesListino" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Listino</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesListino">
                        <h6 class="dropdown-header">Listino:</h6>
                        <a class="dropdown-item" href="{{route('listino.index')}}">Listino</a>
                        <a class="dropdown-item" href="{{route('listino.create')}}">Aggiungi al Listino</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesMarketing" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Marketing</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesMarketing">
                        <h6 class="dropdown-header">Main:</h6>
                        <a class="dropdown-item" href="{{route('fonts.create')}}">Inserisci fonte</a>
                        <a class="dropdown-item" href="{{route('fonts.index')}}">Lista</a>
                        {{--<a class="dropdown-item" href="register.html">Register</a>
                        <a class="dropdown-item" href="forgot-password.html">Forgot Password</a>--}}
                        {{--<div class="dropdown-divider"></div>
                        <h6 class="dropdown-header">Other Functions:</h6>
                        <a class="dropdown-item" href="#">Estrapola</a>--}}
                        {{--<a class="dropdown-item" href="blank.html">Blank Page</a>--}}
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesFiliali" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Filiali</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesFiliali">
                        <h6 class="dropdown-header">Main:</h6>
                        <a class="dropdown-item" href="{{route('filiale.create')}}">Inserisci Filiale</a>
                        <a class="dropdown-item" href="{{route('filiale.index')}}">Lista</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesFiliali" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Fatture</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesFiliali">
                        <h6 class="dropdown-header">Filiali:</h6>
                        @foreach($filiali as $filiale)
                            <a class="dropdown-item" href="{{route('fatture.index', $filiale->id)}}">{{$filiale->nome}}</a>
                        @endforeach
                        {{--<a class="dropdown-item" href="{{route('fatture.index')}}">Lista</a>--}}
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesFiliali" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>DDT</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesFiliali">
                        <h6 class="dropdown-header">Filiali:</h6>
                        @foreach($filiali as $filiale)
                            <a class="dropdown-item" href="{{route('ddt.index', $filiale->id)}}">{{$filiale->nome}}</a>
                        @endforeach
                    </div>
                </li>

                {{--<li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-fw fa-table"></i>
                        <span>{{auth()->user()->magazzino}}</span></a>
                </li>--}}

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesAudio" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Audiop</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesAudio">
                        <h6 class="dropdown-header">Main:</h6>
                        {{--<a class="dropdown-item" href="{{route('filiale.create')}}">Inserisci Audiop.</a>--}}
                        <a class="dropdown-item" href="{{route('audio.index')}}">Lista</a>
                    </div>
                </li>
            @endif

            @if(auth()->user()->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesStat" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Statistiche</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesStat">
                        <h6 class="dropdown-header">Main:</h6>
                        <a class="dropdown-item" href="{{route('statistiche.mese')}}">Mese</a>
                        <a class="dropdown-item" href="{{route('statistiche.anno')}}">Anno</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagesSpecial" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-fw fa-folder"></i>
                        <span>Speciali</span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="pagesSpecial">
                        <h6 class="dropdown-header">Main:</h6>
                        <a class="dropdown-item" href="{{route('resetDB')}}">Reset DB</a>
                        <a class="dropdown-item" href="{{route('clients.import')}}">Importo CL</a>
                        <a class="dropdown-item" href="{{route('product.import')}}">Importo Product</a>
                    </div>
                </li>
            @endif
        </ul>



        <div id="content-wrapper">

            <div class="container-fluid">

                @yield('container')

            </div>
            <!-- /.container-fluid -->

            <!-- Sticky Footer -->
            <footer class="sticky-footer">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright © Your Website 2019</span>
                    </div>
                </div>
            </footer>

        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
@endauth

@section('footer')
<!-- Bootstrap core JavaScript-->
<script src="{{asset('jquery/jquery.min.js')}}"></script>
<script src="{{asset('bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- Core plugin JavaScript-->
<script src="{{asset('jquery-easing/jquery.easing.min.js')}}"></script>

<!-- Page level plugin JavaScript-->
<script src="{{asset('chart.js/Chart.min.js')}}"></script>
<script src="{{asset('datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('datatables/dataTables.bootstrap4.js')}}"></script>

<!-- Custom scripts for all pages-->
<script src="{{asset('js/sb-admin.min.js')}}"></script>

<script src="{{asset('js/jquery.canvasjs.min.js')}}"></script>

<!-- JQuery Data Format -->
<script src="{{asset('js/jquery-dateformat.min.js')}}"></script>

{{--<!-- Demo scripts for this page-->
<script src="{{asset('')}}js/demo/datatables-demo.js"></script>
<script src="{{asset('')}}js/demo/chart-area-demo.js"></script>--}}

    @show

</body>

</html>
