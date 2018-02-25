<?php

namespace App\Http\Controllers;

require __DIR__ . '/../../../vendor/autoload.php';

use App\Http\Controllers\Controller;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\NoNodesAvailableException;

class SystemStatsController extends Controller
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

    $resp["clusterStatus"] = $this->getClusterStatus($client);
    $resp["indexStatus"] = $this->getIndexStatus($client);
    
    if ($resp["clusterStatus"] === "" || $resp["indexStatus"] === "") {
      return "Cluster may be Down.";
    }

    return view('systemstats', [ 'resp' => $resp ]);
  }

  function getClusterStatus($client) {
    try {
      $resp_clusterStatus = $client->cluster()->stats();
      $resp_catAliases = $client->cat()->aliases();
      $resp_clusterHealth = $client->cluster()->health();
    } catch (NoNodesAvailableException $e) {
      return "";
    }

    $clusterStatus["indexCount"] = $resp_clusterStatus["indices"]["count"];
    $clusterStatus["aliasCount"] = count($resp_catAliases);

    $clusterStatus["clusterStatus"] = $resp_clusterHealth["status"];
    $clusterStatus["clusterNodes"] = $resp_clusterHealth["number_of_nodes"];
    $clusterStatus["clusterHealth"]["initializingShards"] = $resp_clusterHealth["initializing_shards"];
    $clusterStatus["clusterHealth"]["unassignedShards"] = $resp_clusterHealth["unassigned_shards"];
    $clusterStatus["clusterHealth"]["delayedUnassignedShards"] = $resp_clusterHealth["delayed_unassigned_shards"];
    $clusterStatus["clusterHealth"]["taskMaxWaitingInQueueMillis"] = $resp_clusterHealth["task_max_waiting_in_queue_millis"];
    $clusterStatus["clusterHealth"]["activeShardsPercentAsNumber"] = $resp_clusterHealth["active_shards_percent_as_number"];

    unset($resp_clusterStatus);
    unset($resp_catAliases);
    unset($resp_catAliases);

    return $clusterStatus;
  }

  function getIndexStatus($client) {

    try {
      $resp_catIndices = $client->cat()->indices();
    } catch (NoNodesAvailableException $e) {
      return "";
    }
    
    $indexStatus["count"] = count($resp_catIndices);
    $indexStatus["open"] = 0;
    $indexStatus["closed"] = 0;
    $indexStatus["green"] = 0;
    $indexStatus["yellow"] = 0;
    $indexStatus["red"] = 0;
    foreach ( $resp_catIndices as $index) {
      switch( $index["health"] ) {
        case "green":
          $indexStatus["green"]++;
          break;
        case "yellow":
          $indexStatus["yellow"]++;
          break;
        case "red":
          $indexStatus["red"]++;
          break;
      }
      switch( $index["status"] ) {
        case "open":
          $indexStatus["open"]++;
          break;
        case "close":
          $indexStatus["closed"]++;
          break;
      }
    }
  
    $indexStatus["enablegreen"] = "-";
    $indexStatus["enableyellow"] = "";
    $indexStatus["enablered"] = "";
    if( $indexStatus["yellow"] != 0 ) {
      $indexStatus["enableyellow"] = "yellow";
      $indexStatus["enablegreen"] = "";
    } 
    if( $indexStatus["red"] != 0 ) {
      $indexStatus["enablered"] = "red";
      $indexStatus["enablegreen"] = "";
    }
    if( $indexStatus["enablegreen"] == "-" ) {
      $indexStatus["enablegreen"] = "green";
    }
  
    unset($resp_catIndices);
    return $indexStatus;
  }
  
  function getStats($client) {
    try {
      $resp_nodesStats = $client->nodes()->stats();
    } catch (NoNodesAvailableException $e) {
      return "";
    }
    if( $resp_nodesStats['_nodes']['successful'] !== 1 ) {
      return "";      
    }

    $nodeStats['searchTotal'] = 0;
    $nodeStats['getTotal'] = 0;
    foreach( $resp_nodesStats['nodes'] as $node ) {
      $nodeStats['searchTotal'] += $node['indices']['search']['query_total'];
      $nodeStats['getTotal'] += $node['indices']['get']['total'];
    }

    return $nodeStats;
  }
}
