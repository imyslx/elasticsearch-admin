@extends('layouts.header')

@section('css')
<link rel="stylesheet" href="css/search.css">
@endsection

@section('js')
@endsection

@section('title', 'test for searchers.')

@section('content')
<div class="container" id="content">
  <h3>■ Search Documents</h3>
  <div class="search-box">
    <div class="row">
      <div class="col search-title">
        データの検索
      </div>
    </div>
    <div class="row">
      <div class="col">
      <form method="GET" action="/search">
        <div class="form-group">
          <label for="indexSelector">Index</label>
          <select class="form-control" id="indexSelector" name="indexSelector">
            <option value=""> 指定無し </option>
@foreach ($indexes as $index)
@if ($index == $input["indexSelector"])
            <option selected> {{ $index }} </option>
@else
            <option> {{ $index }} </option>
@endif
@endforeach
          </select>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="keyIdFilter"> Key-ID </label>
              <input type="text" class="form-control" id="keyIdFilter" name="keyIdFilter" placeholder="key-id">
              <small id="emailHelp" class="form-text text-muted">※選択したIndexのIDに当たる値</small>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="keywordFilter"> キーワード </label>
              <input type="text" class="form-control" id="keywordFilter" name="keywordFilter" placeholder="key-id">
              <small id="emailHelp" class="form-text text-muted"> ※部分一致検索 </small>
            </div>
          </div>
        <!--</div>
        <div class="row"> -->
          <div class="col">
            <div class="form-group">
              <label for="dateFilter"> 投稿日時 </label>
              <div class="input-group">
                <input type="text" class="form-control" id="byear" name="byear" placeholder="2018">
                <input type="text" class="form-control" id="bmonth" name="bmonth" placeholder="2">
                <input type="text" class="form-control" id="bday" name="bday" placeholder="18">
              </div>
                <span class="center">～</span>
              <div class="input-group">
                <input type="text" class="form-control" id="ayear" name="ayear" placeholder="2018">
                <input type="text" class="form-control" id="amonth" name="amonth" placeholder="3">
                <input type="text" class="form-control" id="aday" name="aday" placeholder="18">
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <button type="submit" class="btn btn-primary center">Submit</button>
          </div>
        </div>
      </form>
      </div>
    </div>
  </div>
</div>
@endsection
