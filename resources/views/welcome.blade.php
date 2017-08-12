<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DRABR - Say What You Want To Say!</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- Animation CSS -->
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/cover.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">


    <style>
        .masthead-brand, #div-cover-page {
            color: white;
        }

        .social-feed-box {
            text-align: justify;
        }
    </style>
</head>
<body>

<div class="site-wrapper">

    <div class="site-wrapper-inner">
        <div class="masthead clearfix">
            <div class="inner">
                <h3 class="masthead-brand">DRABR!</h3>
            </div>
        </div>

        <div class="cover-container">
            <div id="div-cover-page">
                <div class="inner cover cover div-show-posts">
                    <h1 class="cover-heading">Welcome to DRABR!</h1>

                    <p class="lead">Say anything what's inside your mind.<br>We won't mind. </p>
                </div>

                <div class="row" class="div-show-posts">
                    <div class="col-lg-12 text-center">
                        <button class="btn btn-success btn-lg" id="btn-search-posts">Show Posts Near Your Location
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <section id="posts" class="hidden">
            <div class="container" id="container-body">
                <div class="row m-b-lg">
                    <div class="col-lg-6 col-lg-offset-3">
                        <div class="search-form">
                            <form action="index.html" method="get">
                                <div class="input-group">
                                    <input type="text" placeholder="Ex. Cats, Dogs, House" name="search"
                                           class="form-control input-lg">

                                    <div class="input-group-btn">
                                        <button class="btn btn-lg btn-primary" type="button">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row m-b-lg">
                    <div class="col-lg-6 col-lg-offset-3">
                        <div class="search-form">
                            <form enctype="multipart/form-data" id="frm_post" action="/save-post" method="POST">
                                {{csrf_field()}}
                                <div class="input-group">
                                    <textarea id="slug" name="slug" class="form-control input-lg" width="100%"
                                              maxlength="100" rows="2" resize="none"></textarea>
                                    <span class="input-group-addon btn btn-primary" id="btn-save-post">Add POST</span>
                                </div>
                                <input type="file" name="file" id="file"/>
                                <input type="hidden" id="lat" name="lat">
                                <input type="hidden" id="lon" name="lon">
                                <input type="hidden" id="locationName" name="locationName">

                            </form>
                            {{--<button type="button" class="btn btn-primary" id="btn-add-post">Add A Post</button>--}}

                        </div>
                    </div>
                </div>

                <div class="wrapper wrapper-content animated fadeInRight" id="div-posts">
                    <div class="row">

                        <div class="col-lg-6 col-lg-offset-3" id="content-posts">

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2 text-center m-t-lg m-b-lg">
                        <p><strong>&copy; 2017 DRABR! Say what's in your mind. We won't mind! </strong></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<input type="hidden" id="_startLat"/>
<input type="hidden" id="_startLon"/>
<input type="hidden" id="_locationName"/>


<!-- Mainly scripts -->
<script src="/js/jquery-3.1.1.min.js"></script>
<script src="/js/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>
<script src="/js/plugins/pace/pace.min.js"></script>
<script src="/js/plugins/wow/wow.min.js"></script>
<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>



<script>

    $(document).ready(function () {

        /* Set page height */
        var width = window.innerWidth
                || document.documentElement.clientWidth
                || document.body.clientWidth;

        var height = window.innerHeight
                || document.documentElement.clientHeight
                || document.body.clientHeight;

        $("#container-body").css('height', height);
        $("#container-body").css('width', width);


        /* FUNCTIONS */
        /* Get User Location */
        getLocation = function () {
            var startPos;
            var geoOptions = {
                timeout: 10 * 1000
            }

            var geoSuccess = function (position) {
                startPos = position;
                console.log("GEOSUCCESS");

                document.getElementById('_startLat').value = startPos.coords.latitude;
                document.getElementById('_startLon').value = startPos.coords.longitude;
                geocodeLatLng();
                document.cookie = "lat="+startPos.coords.latitude;
                document.cookie = "lon="+startPos.coords.longitude;
                document.cookie = "location="+document.getElementById('_locationName').value
                getPosts();
            };
            var geoError = function (error) {
                console.log("GEOIP");
                console.log('Error occurred. Error code: ' + error.code);
                // error.code can be:
                //   0: unknown error
                //   1: permission denied
                //   2: position unavailable (error response from location provider)
                //   3: timed out
                getGeoIp();

            };

            navigator.geolocation.watchPosition(geoSuccess, geoError, geoOptions);
        };

        getGeoIp = function () {
            $.get("/get-ip-location", function (data) {
                console.log(data);
                document.getElementById('_startLat').value = data.lat;
                document.getElementById('_startLon').value = data.lon;
                document.getElementById('_locationName').value = data.city + data.country;
                document.cookie = "lat="+data.lat;
                document.cookie = "lon="+data.lon;
                document.cookie = "location="+data.city + data.country;
                getPosts();
            }, "json");

        };


        getPosts = function () {
            console.log("lat>>"+$("#_startLat").val());
            console.log("lon>>"+$("#_startLon").val());
            $.get("/get-posts",{lat:$("#_startLat").val(),lon:$("#_startLon").val()}).done( function (data) {
                $("#content-posts").html(data);
            });
        };

        $("#btn-save-post").click(function () {
            var lat = document.getElementById('_startLat').value;
            var lon = document.getElementById('_startLon').value;
            var loc = document.getElementById('_locationName').value;
            var input = document.getElementById('file');

            document.getElementById('lat').value = lat;
            document.getElementById('lon').value = lon;
            document.getElementById('locationName').value = loc;

            console.log('test');
            $("#frm_post").submit();
            console.log('post');
        });

        /* BUTTON ACTIONS */

        /* Notify User To Access Accurate Location */
        $("#btn-search-posts").click(function () {
            swal({
                title: "Just One More Step",
                text: "In order to view posts near you, we need to get your exact location. Don't worry, we will keep your location anonymous.",
                type: "info",
                showCancelButton: false,
                confirmButtonColor: "#1A7BB9",
                confirmButtonText: "Use GPS To View Posts", // USE GEOLOCATION
            }).then(function (isConfirm) {
                if (isConfirm) { // RUN Google geolocation
                    getLocation();
                } else { // USE GEO-IP to find location
                    getGeoIp();
                }

                $(".cover-container").slideUp("slow");
                $("#posts").removeClass("hidden");
            });
        });

        $("#btn-add-post").click(function () {
            newPost();
        });


        function geocodeLatLng() {
            var geocoder = new google.maps.Geocoder;
            var _lat = document.getElementById('_startLat').value;
            var _lon = document.getElementById('_startLon').value;
            var latlng = {lat: parseFloat(_lat), lng: parseFloat(_lon)};
            var location = '';
            geocoder.geocode({'location': latlng}, function (results, status) {
                if (status === 'OK') {
                    if (results[0]) {
                        location = results[0].formatted_address;
                        console.log(results[0].formatted_address);
                        document.getElementById('_locationName').value = results[0].formatted_address;

                    } else {
                        window.alert('No results found');
                    }
                } else {
                    window.alert('Geocoder failed due to: ' + status);
                }

            });

        }


    });

</script>

<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQTpXj82d8UpCi97wzo_nKXL7nYrd4G70">
</script>


</body>
</html>
