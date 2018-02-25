<?php

namespace App\Http\Controllers;

require __DIR__ . '/../../../vendor/autoload.php';

use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;

class IndexesController extends Controller
{
  /**
   * 指定ユーザーのプロフィール表示
   *
   * @param  int  $id
   * @return Response
   */

  public function main() {
    $hosts = [
      'host' => config('database.connections.elasticsearch.host')
    ];
    $logger = ClientBuilder::defaultLogger(__DIR__ . '/../../../logs/elasticsearch.log');
    $client = ClientBuilder::create()
                    ->setHosts($hosts)
                    ->setLogger($logger)
                    ->build();

    $resp["indexStatus"] = $this->getIndexStatus($client);
    
    if ( $resp["indexStatus"] === "" ) {
      return "Cluster may be Down.";
    }

    return view('indexes', [ 'resp' => $resp ]);
  }


  function getIndexStatus($client) {

    try {
      $resp_catIndices = $client->cat()->indices();
    } catch (NoNodesAvailableException $e) {
      return "";
    }
    $indexStatus = "";
    $indexes = Array();
    foreach ( $resp_catIndices as $index ) {
      $index_tmp["name"] = $index["index"];
      $index_tmp["status"] = $index["status"];
      $index_tmp["health"] = $index["health"];
      $index_tmp["docs"] = $index["docs.count"];
      $index_tmp["storage"] = $index["store.size"];
      if( $index_tmp["status"] == "close" ) {
        $index_tmp["row_class"] = "closed";
      } elseif( $index_tmp["health"] == "green" ) {
        $index_tmp["row_class"] = "green";
      } elseif( $index_tmp["health"] == "yellow" ) {
        $index_tmp["row_class"] = "yellow";
      } elseif( $index_tmp["health"] == "red" ) {
        $index_tmp["row_class"] = "red";
      }
      $indexes[] = $index_tmp;
    }
  
    //var_dump($resp_catIndices);
    unset($resp_catIndices);
    return $indexes;
  }
  
}
