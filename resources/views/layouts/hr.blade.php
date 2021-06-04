<!doctype html>
<html lang="en">
<head>
    <title>
        @section('title')
        @show
    </title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">
        let hrToken = JSON.parse(localStorage.getItem('hrToken'));
        if(hrToken==null){
            window.location.href = "/hr-login";
        }
    </script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('./css/style.css')}}">
    <link rel="stylesheet" href="{{asset('./css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-sticky d-flex justify-content-center">
                    <a href="/candidates-list"><img class="logo-sidebar my-3" src="{{asset('resources/img/logo-hybrid-technologies.svg')}}" alt="HYBRID TECHNOLOGIES">
                </div>
                <div class="sidebar-sticky">
                    <ul class="nav flex-column">
                        <span class="icon icon-people"></span>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="/candidates-list">
                                <img src="{{asset('../resources/img/people.svg')}}" height="16" class="m-3" alt="people-icon">List survey
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="row mt-4 justify-content-end">
                    <div class="col-md-4 col-sm-12 col-12 d-flex justify-content-md-center">
                        <div class="dropdown">
                            <button class="dropdown-toggle btn bg-transparent" id="hrName" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">123</button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" id="logoutBtn">Logout</a>
                                </div>
                            <img src="{{asset('../resources/img/avt.svg')}}" height="32" class="rounded-circle ml-2">
                        </div>
                    </div>
                </div>
                @yield('content') 
            </main>
        </div>
    </div>
    <script src="{{asset('./js/main.js')}}"></script>
    <script>
        document.getElementById("hrName").innerHTML=hrToken.user.name;
        document.getElementById("logoutBtn").addEventListener("click", logoutFunc);
        function logoutFunc(e){
            window.localStorage.removeItem('hrToken');
            window.location.href = "/hr-login";
        }
    </script>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="{{asset('./js/bootstrap.min.js')}}" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>