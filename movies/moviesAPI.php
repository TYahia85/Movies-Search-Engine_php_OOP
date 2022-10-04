<?php

require_once 'moviesConstructor.php';

class MovieAPIs extends MoviesConstructor
{
    public $title;
    public $year;
    public function movieLookUp($title,$year)
    {
        //********** Contact OMDB API *********\\
        // API url
        $url = "http://www.omdbapi.com/?t=$title&y=$year&apikey=$this->api_key&";

        // initilize curl
        $ch = curl_init();
    
        // set curl options
        curL_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        // execute curl 
        $response = curl_exec($ch);
        
        // cache api reponse
        $omdb_data = json_decode($response,true);
        curl_close($ch);
        
        if($e = curl_error($ch))
        {
            return $e;
        }

        // get movie IMDB id
        $omdb_imdbID = $omdb_data['imdbID'];

        //********** Contact YTS API *********\\

        // API url
        $url = "https://yts.mx/api/v2/list_movies.json?query_term=$title";

        // initilize curl
        $ch = curl_init();
    
        // set curl options
        curL_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);

        // execute curl 
        $response = curl_exec($ch);
        
        // cache api reponse
        $yts_data = json_decode($response,true);
        curl_close($ch);
        
        if($e = curl_error($ch))
        {
            return $e;
        }

        // get movie IMDB id
        $yts_imdbID = $yts_data['data']['movies'][1]['imdb_code'];
        
        // match movie id to proceed to torrent links
        if($omdb_imdbID == $yts_imdbID){
            
            // get torrents links
            $torrents = $yts_data['data']['movies'][0]['torrents'];

            // merge both arrays and return final result
            $movieData = array_merge($omdb_data,$torrents);
            return $movieData;
        }else{
            echo 'No download links available for this movie';
        }

    }
}

// instanciate an object
$movie = new MovieAPIs();

// call the method
$query = $movie->movieLookUp('hulk',2010);
print_r($query);