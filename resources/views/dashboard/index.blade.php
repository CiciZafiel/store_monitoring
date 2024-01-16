@extends('layout.app')
@section('content')
<header class="py-3 mb-3 border-bottom">
    <div class="container-fluid d-grid gap-3 align-items-center" style="grid-template-columns: 1fr 2fr;">
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center col-lg-4 mb-2 mb-lg-0 link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
        </a>
        <ul class="dropdown-menu text-small shadow">
          {{-- <li><a class="dropdown-item active" href="#" aria-current="page">Overview</a></li>
          <li><a class="dropdown-item" href="#">Inventory</a></li>
          <li><a class="dropdown-item" href="#">Customers</a></li>
          <li><a class="dropdown-item" href="#">Products</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">Reports</a></li>
          <li><a class="dropdown-item" href="#">Analytics</a></li> --}}
        </ul>
      </div>

      <div class="d-flex align-items-center">
        <form class="w-100 me-3" role="search">
          <input type="search" class="form-control" placeholder="Search..." aria-label="Search">
        </form>

        <div class="flex-shrink-0 dropdown">
          <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small shadow">
            
            {{-- <li><a class="dropdown-item" href="#">New project...</a></li>
            <li><a class="dropdown-item" href="#">Settings</a></li>
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Sign out</a></li> --}}
          </ul>
        </div>
      </div>
    </div>
  </header>

  <div class="container-fluid pb-3">
    <div class="d-grid gap-3" style="grid-template-columns: 1fr 2fr;">
      <div class="bg-body-tertiary border rounded-3">
        <h3><center>Store List</center></h3>
        <ol style="1">
            <li>Samsung Concept Store Fisher Mall POS</li>
            <li>Samsung Concept Store SM Sangandaan</li>
            <li>SM San Jose POS</li>
            <li>OPPO Concept Store SM Southmall</li>
        </ol>
      </div>
      <div class="bg-body-tertiary border rounded-3">
        <table class="table table-hover table-striped table-dark">
            <thead>
                {{-- Status not status on DB, Status in result on ping --}}
                <tr>
                  <th scope="col">WarehouseCode</th>
                  <th scope="col">Store</th>
                  <th scope="col">IP</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">703</th>
                  <td>Samsung Concept Store Fisher Mall POS</td>
                  <td>192.168.12.251</td>
                  <td><button type="button" class="btn btn-success">Online</button></td>
                </tr>
                <tr>
                  <th scope="row">710</th>
                  <td>Samsung Concept Store SM Sangandaan</td>
                  <td>192.168.29.253</td>
                  <td><button type="button" class="btn btn-danger">Offline</button></td>
                </tr>
                <tr>
                  <th scope="row">714</th>
                  <td>SM San Jose POS</td>
                  <td>192.168.42.252</td>
                  <td><button type="button" class="btn btn-success">Online</button></td>
                </tr>
                <tr>
                    <th scope="row">717</th>
                    <td>OPPO Concept Store SM Southmall</td>
                    <td>192.168.13.251</td>
                    <td><button type="button" class="btn btn-danger">Offline</button></td>
                  </tr>
              </tbody>
          </table>
      </div>
    </div>
  </div>

  <div class="b-example-divider"></div>

  {{-- <nav class="py-2 bg-body-tertiary border-bottom">
    <div class="container d-flex flex-wrap">
      <ul class="nav me-auto">
        <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2 active" aria-current="page">Home</a></li>
        <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Features</a></li>
        <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Pricing</a></li>
        <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">FAQs</a></li>
        <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">About</a></li>
      </ul>
      <ul class="nav">
        <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Login</a></li>
        <li class="nav-item"><a href="#" class="nav-link link-body-emphasis px-2">Sign up</a></li>
      </ul>
    </div>
  </nav> --}}
@endsection