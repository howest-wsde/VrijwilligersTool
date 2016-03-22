<?php
require 'vendor/autoload.php';

$httpHost = ['http://localhost:9200'];
$httpsHost = ['https://localhost:9200'];

$client = Elasticsearch\ClientBuilder::create()
    >setHosts($httpHost)->build();

//create

// $params = [
//     'index' => 'my_index',
//     'type' => 'my_type',
//     'id' => 'my_id',
//     'body' => ['testField' => 'abc']
// ];
// $response = $client->index($params);
// print_r($response);

//get

// $params = [
//     'index' => 'my_index',
//     'type' => 'my_type',
//     'id' => 'my_id'
// ];
// $response = $client->get($params);
// print_r($response);

//search

// $params = [
//     'index' => 'my_index',
//     'type' => 'my_type',
//     'body' => [
//         'query' => [
//             'match' => [
//                 'testField' => 'abc'
//             ]
//         ]
//     ]
// ];
// $response = $client->search($params);
// print_r($response);

//delete

// $params = [
//     'index' => 'my_index',
//     'type' => 'my_type',
//     'id' => 'my_id'
// ];
//
// $response = $client->delete($params);
// print_r($response);

print_r("DONE".PHP_EOL);
