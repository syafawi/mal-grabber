<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
require 'vendor/autoload.php';
use Stichoza\GoogleTranslate\TranslateClient;
set_time_limit(0);
if (isset($_GET['id'])){
        $id = $_GET['id'];
        $url = "https://api.jikan.moe/v3/anime/{$id}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
            header('Content-Type: application/json');
            
         curl_close($ch);
        // echo $result;
        // trigger_error($result);
        $hasil=json_decode($result,true);

        $tr = new TranslateClient(); // Default is from 'auto' to 'en'
        $tr->setSource('en'); // Translate from English
        $tr->setTarget('id'); // Translate to Georgian
        $sinopsis = $tr->translate($hasil['synopsis']);
        
        $prod = null;
        foreach($hasil['producers'] as $producer){
            $prod .=  $producer['name'].',';
        }
        $stud = null;
        foreach($hasil['studios'] as $studio){
            $stud .=  $studio['name'].',';
        }
        $gen = null;
        for($i = 0; $i < count($hasil['genres']); $i++){ //count() = menghitung total array di dalam genres
            $gen .= $hasil['genres'][$i]['name'].',';
            
        }

        $arr = Array (
            "mal_id" => $hasil['mal_id'],
            "url" => $hasil['url'],
            "image_url" => $hasil['image_url'],
            "trailer_url" => $hasil['trailer_url'],
            "title" => $hasil['title'],
            "title_english" => $hasil['title_english'],
            "title_japanese" => $hasil['title_japanese'],
            "title_japanese" => $hasil['title_japanese'],
            "title_synonyms" => $hasil['title_synonyms'],
            "type" => $hasil['type'],
            "source" => $hasil['source'],
            "episodes" => $hasil['episodes'],
            "status" => $hasil['status'],
            "aired" => $hasil['aired']['string'],
            "duration" => $hasil['duration'],
            "rating" => $hasil['rating'],
            "score" => substr($hasil['score'], 0,5),
            "synopsis" => $sinopsis,
            "season" => $hasil['premiered'],
            "genres" => substr($gen, 0, -1),
            "producers" => substr($prod, 0, -1),
            "studios" => substr($stud, 0, -1)
        );
        $json = json_encode($arr, JSON_PRETTY_PRINT);
        echo($json);
    }
    else {
        echo "Contoh : https://mal.syafawi.my.id/?id=38816";
        echo "<br> masukan id dari Myanimelist contoh : https://myanimelist.net/anime/38816/Hello_World ";
    }
?>
