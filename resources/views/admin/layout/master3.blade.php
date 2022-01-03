
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/admin/images/favicon.png')}}">
        <title>Meen : Admin Panel</title> 
        <meta name="keywords" content="Meen." />
        <meta name="author" content="Meen" />
        <link href="https://www.meen.com.com/admin" rel="canonical" />
        <meta name="Classification" content="meen" />
        <meta name="abstract" content="https://www.meen.com/admin" />
        <meta name="audience" content="All" />
        <meta name="robots" content="index,follow" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:title" content="Meen Admin Panel" /> 
        <meta property="og:url" content="https://www.meen.com/admin" /> 
        <meta property="og:site_name" content="meen" />
        <meta name="googlebot" content="index,follow" />
        <meta name="distribution" content="Global" />
        <meta name="Language" content="en-us" />
        <meta name="doc-type" content="Public" />
        <meta name="site_name" content="meen" />
        <meta name="url" content="https://www.meen.com/admin" />
        <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/css/style.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{asset('assets/admin/css/et-line-font/et-line-font.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/css/themify-icons/themify-icons.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/css/simple-lineicon/simple-line-icons.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables/css/dataTables.bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/css/skins/_all-skins.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/formwizard/jquery-steps.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/dropify/dropify.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/admin/css/font/stylesheet.css')}}">
    <style type="text/css">
        /* Chart.js */
                    @-webkit-keyframes chartjs-render-animation {
                        from {
                            opacity: 0.99;
                        }
                        to {
                            opacity: 1;
                        }
                    }
                    @keyframes chartjs-render-animation {
                        from {
                            opacity: 0.99;
                        }
                        to {
                            opacity: 1;
                        }
                    }
                    .chartjs-render-monitor {
                        -webkit-animation: chartjs-render-animation 0.001s;
                        animation: chartjs-render-animation 0.001s;
                    }
                    .search-select-btn .select-wrapper input.select-dropdown{
                           border: 1px solid #d2d6de!important; 
            padding: 0 10px!important;
            border-radius: 5px!important;     
                    }
                    .search-select-btn .select-wrapper span.caret { 
            right: 10px; 
        }
    </style>
    <style>
            .loader {
                text-align: center;
                vertical-align: middle;
                position: fixed;
                display: flex;
                background: #fdfbfb;
                padding: 150px;
                box-shadow: 0px 40px 60px -20px rgba(0, 0, 0, 0.2);
                width:100%;
                z-index:500000;
                height: 100%;
                padding-left: 43%;
                padding-top: 30%;
            }

            .loader span {
                display: block;
                width: 20px;
                height: 20px;
                background: #ec1d38;
                border-radius: 50%;
                margin: 0 5px;
                box-shadow: 0 2px 2px rgba(0, 0, 0, 0.2);
            }


            .loader span:nth-child(2) {
                background: #f07e6e;
            }

            .loader span:nth-child(3) {
                background: #84cdfa;
            }

            .loader span:nth-child(4) {
                background: #5ad1cd;
            }

            .loader span:not(:last-child) {
                animation: animate 1.5s linear infinite;
            }

            @keyframes animate {
                0% {
                    transform: translateX(0);
                }

                100% {
                    transform: translateX(30px);
                }
            }

            .loader span:last-child {
                animation: jump 1.5s ease-in-out infinite;
            }

            @keyframes jump {
                0% {
                    transform: translate(0, 0);
                }
                10% {
                    transform: translate(10px, -10px);
                }
                20% {
                    transform: translate(20px, 10px);
                }
                30% {
                    transform: translate(30px, -50px);
                }
                70% {
                    transform: translate(-150px, -50px);
                }
                80% {
                    transform: translate(-140px, 10px);
                }
                90% {
                    transform: translate(-130px, -10px);
                }
                100% {
                    transform: translate(-120px, 0);
                }
            }


            .loaderDiv{
                position: fixed;
                z-index: 5000000000001;
                text-align: center;
                top: 30%;
                left: 38%;
            }

            .loaderDiv p{
                color:#ec1d38!important;
            }
        </style>
</head>
    <body class="skin-blue sidebar-mini">
        <div class="loading loaderDiv">
            <img class="mb-2" src="{{asset('assets/admin/images/logo.png')}}" alt="logo">
            <p>Please wait while page is loading..</p>
        </div>
        <div class="loading loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="wrapper boxed-wrapper">

            <!-- Navbar -->
            @include('admin.layout.header')
            <!-- /.navbar -->
            <!-- Main Sidebar Container -->
            <!-- <div class="dashboard-section"> -->
            @include('admin.layout.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <!-- <div class="content-wrapper"> -->
            <!-- <div id="loader" class="lds-dual-ring hidden overlay"></div> -->
            <!-- Content Header (Page header) -->
            @yield('content')
            <!-- /.content-header -->
            <!-- </div> -->
            <!-- Main Footer -->
            @include('admin.layout.footer')
            <!-- </div> -->
            <!-- /.content-wrapper -->

        </div>
</body>
<script src="{{asset('assets/admin/js/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="{{asset('assets/admin/js/bizadmin.js')}}"></script>
<script src="{{asset('assets/admin/js/bootstrap.min.js')}}"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript" src="https://mdbootstrap.com/api/snippets/static/download/MDB-Pro_4.19.1/js/mdb.min.js"></script>
<script type="text/javascript" src="https://mdbootstrap.com/wp-content/themes/mdbootstrap4/js/bundles/4.19.1/compiled-addons.min.js"></script>
<script type="text/javascript" src="https://mdbootstrap.com/wp-content/themes/mdbootstrap4/js/plugins/mdb-plugins-gathered.min.js"></script>

<script src="{{asset('assets/admin/js/demo.js')}}"></script>
<script type="text/javascript">
            var $loading = $('.loading').hide();
            $(document)
                    .ajaxStart(function () {
                        //ajax request went so show the loading image
                        $loading.show();
                    })
                    .ajaxStop(function () {
                        //got response so hide the loading image
                        $loading.hide();

                    });
    
                    // Material Select Initialization
                    $(document).ready(function () {
                        $(".mdb-select").materialSelect();
                    });
                
</script>
</body>

</html>