<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Torann\GeoIP\GeoIPFacade as GeoIP;

class ApiController extends Controller
{
    public function showPostsByArea()
    {
        $posts = DB::table('post')
            ->select('id', 'slug', 'url', 'created_at', 'id as distance')
            ->groupBy('id')
            ->orderBy('created_at', 'DESC')
            ->get();
        return view('welcome')->with('posts', $posts);
    }


    /*Get Location By IP*/
    public function getLocationByIp()
    {
        // get guest location
        $location = GeoIP::getLocation();

        // if not able to get, use remote adddr
        if (!$location) {
            $ip = $_SERVER["REMOTE_ADDR"];
            $location = GeoIP::getLocation($ip);
        }
        return $location;
    }

    public function getPosts(Request $request)
    {
        $data = $request->all();
        $user_lat = $data['lat'];
        $user_lon = $data['lon'];

        $posts = DB::table('post')
            ->select('id', 'slug', 'url', 'created_at', 'location',
                DB::raw('3959 * acos(cos(radians(' . $user_lat . '))
                        * cos(radians(lat))
                        * cos(radians(lon)-radians(' . $user_lon . '))
                        + sin(radians(' . $user_lat . '))
                        * sin(radians(lat))) as distance'))
            ->groupBy('id')
            ->having('distance', '<=', 5)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('show-post')->with('posts', $posts);
    }

    public function savePost(Request $request)
    {
        $location = GeoIP::getLocation();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file->move('uploads', $file->getClientOriginalName());

            $new_post = new Post();
            $new_post->title = $request->get('slug');
            $new_post->slug = $request->get('slug');
            $new_post->lat = $request->get('lat');
            $new_post->lon = $request->get('lon');
            $new_post->file = $file->getClientOriginalName();
            $new_post->url = '/uploads/' . $new_post->file = $file->getClientOriginalName();
            $new_post->location = $request->get('locationName');
            $new_post->save();
        } else {
            $new_post = new Post();
            $new_post->title = $request->get('slug');
            $new_post->slug = $request->get('slug');
            $new_post->lat = $request->get('lat');
            $new_post->lon = $request->get('lon');
            $new_post->file = 'none';
            $new_post->url = 'none';
            $new_post->location = $request->get('locationName');
            $new_post->save();
        }
        return $this->showPostsByArea();
    }

    public function newPost()
    {
        return view('new-post');
    }


    public function getRadius()
    {

    }
}
