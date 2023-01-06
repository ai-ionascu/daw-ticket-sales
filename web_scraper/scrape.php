<?php

require 'vendor/autoload.php';

$httpClient = new \GuzzleHttp\Client();

$operators = array('astra', 'cfr', 'interregional', 'regio', 'softrans', 'tfc');

function get_dep_stations(){

    global $httpClient, $operators;
    $stations = array();

    foreach ($operators as $operator){

        $response = $httpClient->get('https://mytrain.ro/ro/operatori/'.$operator);
    
        $htmlString = (string) $response->getBody();
    
        // HTML is often wonky, this suppresses a lot of warnings
        libxml_use_internal_errors(true);
    
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
    
        $xpath = new DOMXPath($doc);
    
        $station_names = $xpath->evaluate('//div[@class="stations"]//a');
        $station_links = $xpath->evaluate('//div[@class="stations"]//a/@href');
    
        for ($i=0; $i<count($station_names); $i++){
            $index = $station_names[$i]->textContent;
            if (!array_key_exists($index, $stations)){
                $stations[$index] = $station_links[$i]->textContent;
            }
        }
    }
    return $stations;
}

$dep_stations = get_dep_stations();

// echo count($stations)."\n";

// foreach ($stations as $key => $value) {
//     echo "$key => $value \n";
// }

function get_trains($stations){
    global $httpClient;
    $trains = array();
    foreach ($stations as $name => $link){
        $response = $httpClient->get('https://mytrain.ro'.$link);
        $htmlString = (string) $response->getBody();
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);
        $train_names = $xpath->evaluate('//table[@class="table"]/tbody/tr/td[1]/a');
        $train_links = $xpath->evaluate('//table[@class="table"]/tbody/tr/td[1]/a/@href');
        $train_operators = $xpath->evaluate('//table[@class="table"]/tbody/tr/td[5]/a');

        for ($i=0; $i<count($train_names); $i++){
            $index = $train_names[$i]->textContent;
            if (!array_key_exists($index, $trains)){
                $trains[$index]['link'] = $train_links[$i]->textContent;
                $trains[$index]['operator'] = $train_operators[$i]->textContent;
            }
        }
    }
    return $trains;
}

$trains = get_trains($dep_stations);

print_r($trains);

echo count($trains)."\n";

function get_routes($trains){

    global $httpClient;
    $all_routes_data = array();

    foreach ($trains as $name => $details){
        $response = $httpClient->get('https://mytrain.ro'.$details['link']);
        $htmlString = (string) $response->getBody();
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadHTML($htmlString);
        $xpath = new DOMXPath($doc);
        $route_info = $xpath->evaluate('//table[@class="table"]/tbody/tr');
        $route_data = array();
        foreach ($route_info as $key => $val){
            $line = (string) $val->textContent;
            // $line = str_replace("             ","!",$line);
            $route_data[$key] = explode("             ", $line);
            foreach ($route_data[$key] as $ind => $item){
                $route_data[$key][$ind] = trim($item);
                if ($ind == 0){
                    unset($route_data[$key][$ind]);
                }
            }
            $route_data[$key] = array_values($route_data[$key]);
        }
        array_push($all_routes_data, $route_data);
    }

    $routes = array();
    $stations = array();

    foreach ($all_routes_data as $route_key => $route_data){

        $routes[$route_key]['train'] = array_keys($trains)[$route_key];
        $routes[$route_key]['departure'] = $all_routes_data[$route_key][0][1];
        $routes[$route_key]['departure_time'] = $all_routes_data[$route_key][0][2];
        $routes[$route_key]['arrival'] = $all_routes_data[$route_key][count($all_routes_data[$route_key])-1][3];
        $routes[$route_key]['arrival_time'] = $all_routes_data[$route_key][count($all_routes_data[$route_key])-1][4];
        if (str_contains($all_routes_data[$route_key][count($all_routes_data[$route_key])-1][4], '(a doua zi)')) {
            $routes[$route_key]['next_day_arrival'] = 1;
        }
        else {
            $routes[$route_key]['next_day_arrival'] = 0;
        }

        $total_dist = 0;

        foreach ($route_data as $index => $values){
            $dist = trim($route_data[$index][5], ' km');
            if (strlen($route_data[$index][0]) == 0) {
                $total_dist += (float) $dist;
            }
            else {
                $total_dist += (float) $dist;
                $routes[$route_key]['stations'][$index]['distance'] = $total_dist;
                $routes[$route_key]['stations'][$index]['name'] = $route_data[$index][3];
                $routes[$route_key]['stations'][$index]['order'] = $route_data[$index][0];
                $routes[$route_key]['stations'][$index]['arrival_time'] = $route_data[$index][4];
                $total_dist = 0;
                if (str_contains($route_data[$index][4], '(a doua zi)')) {
                    $routes[$route_key]['stations'][$index]['next_day_arrival'] = 1;
                }
                else {
                    $routes[$route_key]['stations'][$index]['next_day_arrival'] = 0;
                }
                if ($index < count($route_data)-1){
                    $routes[$route_key]['stations'][$index]['departure_time'] = $route_data[$index+1][2];
                    if (str_contains($route_data[$index+1][2], '(a doua zi)')) {
                        $routes[$route_key]['stations'][$index]['next_day_departure'] = 1;
                    }
                    else {
                        $routes[$route_key]['stations'][$index]['next_day_departure'] = 0;
                    }
                }
            } 
        }  
    }
    foreach ($routes as $route_key => $route_val){
        $routes[$route_key]['stations'] = array_values($routes[$route_key]['stations']);
    }

    return $routes;
}

// $routes = get_routes(array_slice($trains,0,2));
// print_r($routes);

function search_nested($item, $arr){
    foreach ($arr as $k => $v){
        if (in_array($item, $v)){
            return $k;
        }
    }
    return null;
}

function get_stations($routes){

    $stations = array();
    foreach ($routes as $route){
        $dep_lookup = search_nested($route['departure'], $stations);
        if (is_null($dep_lookup)){
            $stations[] = array('name' => $route['departure'], 'is_main_station' => 1);
        }
        else {
            if ($stations[$dep_lookup]['is_main_station'] == 0) {
                $stations[$dep_lookup]['is_main_station'] = 1;
            }
        }

        $arr_lookup = search_nested($route['arrival'], $stations);
        if (is_null($arr_lookup)){
            $stations[] = array('name' => $route['arrival'], 'is_main_station' => 1);
        }
        else {
            if ($stations[$arr_lookup]['is_main_station'] == 0) {
                $stations[$arr_lookup]['is_main_station'] = 1;
            }
        }

        foreach ($route['stations'] as $key => $details){
            $st_lookup = search_nested($details['name'], $stations);
            if (is_null($st_lookup)){
                $stations[] = array('name' => $details['name'], 'is_main_station' => 0);
            }
        }
    }

    return $stations;
}

// $stations = get_stations($routes);
// print_r($stations);

?>