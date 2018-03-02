<?php

namespace App\Http\Controllers;

require __DIR__ . '/../../../vendor/autoload.php';

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;

class SearchController extends Controller
{
  /**
   * 指定ユーザーのプロフィール表示
   *
   * @param  int  $id
   * @return Response
   */

  public function main(Request $request) {

    $input = $request->all();
    $input = $this->initFormInputs($input);
    var_dump($input);
    $hosts = [
      'host' => config('database.connections.elasticsearch.host')
    ];
    $logger = ClientBuilder::defaultLogger(__DIR__ . '/../../../logs/elasticsearch.log');
    $client = ClientBuilder::create()
                    ->setHosts($hosts)
                    ->setLogger($logger)
                    ->build();

    $resp = $this->getIndexes($client);
    
    if ( $resp === "" ) {
      return "Cluster may be Down.";
    }

    return view('serach', [ 'indexes' => $resp, 'input' => $input ]);
  }

  function initFormInputs($input) {
    $params = [
      "indexSelector", "keyIdFilter", "keywordFilter", "byear", "bmonth", "bday", "ayear", "amonth", "aday"
    ];
    foreach($params as $param) {
      if(! array_key_exists($param, $input) ) {
        $input[$param] = null;
      }
    }

    return $input;
  }

  function getIndexes($client) {

    try {
      $resp_catIndices = $client->cat()->indices();
    } catch (NoNodesAvailableException $e) {
      return "";
    }
    $indexes = Array();
    foreach ( $resp_catIndices as $index ) {
      $indexes[] = $index["index"];
    }
  
    //var_dump($resp_catIndices);
    unset($resp_catIndices);
    return $indexes;
  }
  
}
