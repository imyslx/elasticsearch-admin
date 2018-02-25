@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="css/systemstats.css">
@endsection

@section('title', 'test for searchers.')

@section('content')

    <div class="container" id="content">
      <div class="row">
        <div class="col">
          <div class="row">
            <div class="col">
              <h4>■ Cluster Status</h4>
            </div>
          </div> <!-- End of Row. -->
          <div class="row">
            <div class="col">
              <table class="status table">
                <tbody>
                  <tr>
                    <th>Nodes</th>
                    <td> {{ $resp['clusterStatus']['clusterNodes'] }} </th>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <td class="{{ $resp['clusterStatus']['clusterStatus'] }}"> {{ $resp['clusterStatus']['clusterStatus'] }} </th>
                  </tr>
                  <tr>
                    <th>Total Indexes</th>
                    <td> {{ $resp['clusterStatus']['indexCount'] }} </th>
                  </tr>
                  <tr>
                    <th>Aliases</th>
                    <td> {{ $resp['clusterStatus']['aliasCount'] }} </th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div> <!-- End of Row. -->
        </div>
        <div class="col">
          <div class="row">
            <div class="col">
              <h4>■ Index Status</h4>
            </div>
          </div> <!-- End of Row. -->
          <div class="row">
            <div class="col">
              <table class="status table">
                <tbody>
                  <tr>
                    <th>Open</th>
                    <td> {{ $resp['indexStatus']['open'] }} </th>
                  </tr>
                  <tr>
                    <th>Closed</th>
                    <td> {{ $resp['indexStatus']['closed'] }} </th>
                  </tr>
                  <tr>
                    <th>Status [green]</th>
                    <td class="{{ $resp['indexStatus']['enablegreen'] }}"> {{ $resp['indexStatus']['green'] }} </th>
                  </tr>
                  <tr>
                    <th>Status [yellow]</th>
                    <td class="{{ $resp['indexStatus']['enableyellow'] }}"> {{ $resp['indexStatus']['yellow'] }} </th>
                  </tr>
                  <tr>
                    <th>Status [red]</th>
                    <td class="{{ $resp['indexStatus']['enablered'] }}"> {{ $resp['indexStatus']['red'] }} </th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div> <!-- End of Row. -->
        </div>
      </div> <!-- End of Row. -->
    </div>
@endsection