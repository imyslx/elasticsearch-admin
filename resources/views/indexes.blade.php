@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="css/indexes.css">
@endsection

@section('js')
<script>
  jQuery(function($){
    $.extend( $.fn.dataTable.defaults, { 
        language: {
            url: "http://cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Japanese.json"
        } 
    });

    $("#index-table").DataTable();
  });
</script>
@endsection

@section('title', 'test for searchers.')

@section('content')
<div class="container" id="content">
      <div class="row">
        <div class="col">
          <div class="row">
            <div class="col">
              <h4>■ IndexList</h4>
            </div>
          </div> <!-- End of Row. -->
          <div class="row">
            <div class="col">
              <table class="status table" id="index-table">
                <thead>
                  <tr>
                    <th>IndexName</th>
                    <th>Status</th>
                    <th>Health</th>
                    <th>Docs</th>
                    <th>StorageSize</th>
                  </tr>
                </thead>
                <tbody>
@foreach ( $resp["indexStatus"] as $index )
                  <tr class="{{ $index['row_class'] }}">
                    <th>
                      {{ $index["name"] }}
                    </th>
                    <td>
                      {{ $index["status"] }}
                    </td>
                    <td>
                      {{ $index["health"] }}
                    </td>
                    <td>
                      {{ $index["docs"] }}
                    </td>
                    <td>
                      {{ $index["storage"] }}
                    </td>
                  </tr>
@endforeach
                </tbody>
              </table>
            </div>
          </div> <!-- End of Row. -->
        </div>
      </div> <!-- End of Row. -->
    </div>

@endsection