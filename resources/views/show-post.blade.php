<!-- Bootstrap core CSS -->
<link href="/css/bootstrap.min.css" rel="stylesheet">

<!-- Animation CSS -->
<link href="/css/animate.css" rel="stylesheet">
<link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="css/plugins/sweetalert/sweetalert.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="/css/cover.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">


@foreach($posts as $post)
    <div class="social-feed-box">

        <div class="social-avatar">
            <div class="media-body">
                <small class="text-muted">{{$post->created_at}} at {{$post->location}} <i>{{$post->distance}} miles
                        away</i></small>
            </div>
        </div>
        <div class="social-body">
            <p>
                {{$post->slug}}
            </p>
            @if($post->url != '' && $post->url != 'none')
                <p>
                    <img src="{{$post->url}}" class="img-responsive">
                </p>
            @endif
        </div>
    </div>
@endforeach