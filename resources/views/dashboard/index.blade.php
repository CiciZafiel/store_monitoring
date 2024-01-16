@extends('layout.app')
@section('content')


  <div class="container-fluid pb-3">
    <div class="d-grid gap-3" style="grid-template-columns: 1fr 2fr;">
      <div class="bg-body-tertiary border rounded-3">
        <h3><center>Store List</center></h3>
        <ol style="1">
            {{-- <li>Samsung Concept Store Fisher Mall POS</li>
            <li>Samsung Concept Store SM Sangandaan</li>
            <li>SM San Jose POS</li>
            <li>OPPO Concept Store SM Southmall</li> --}}
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
              <tbody id="storeAvailabilityTBL">
                <tr>
                  <th scope="row">703</th>
                  <td>Samsung Concept Store Fisher Mall POS</td>
                  <td>192.168.12.251</td>
                  <td><button type="button" class="btn btn-success">Online</button></td>
                </tr>
                {{-- <tr>
                  <th scope="row">710</th>
                  <td>Samsung Concept Store SM Sangandaan</td>
                  <td>192.168.29.253</td>
                  <td><button type="button" class="btn btn-danger">Offline</button></td>
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
                  </tr> --}}
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




@section('script')
  <script>
    var store_lists = 'test';

    $(function(){
      

      getStoreLists();
    });

    function getStoreLists(){
      $.get('/api/store-lists', function(response) {
          store_lists = response
          // console.log(response);
          store_lists.forEach(element => {
              // console.log(element);
              var tr = `<tr>
                  <th scope="row">${element.warehouse_code}</th>
                  <td>${element.store_name}</td>
                  <td>${element.store_ip}</td>
                  <td id="${element.warehouse_code}"></td>
                </tr>`;

              $('#storeAvailabilityTBL').append(tr)
              pingStoreIP(element.warehouse_code.trim(), element.store_ip.trim());
          });
      });
    }

    function pingStoreIP(warehouse_code, store_ip){
      $.post('/api/store-availability',{ ip: store_ip },function(response){
          if(response){
            console.log(warehouse_code);
            $(`#${warehouse_code}`).append('<button type="button" class="btn btn-success">Online</button>');
          }
          else{
            $(`#${warehouse_code}`).append('<button type="button" class="btn btn-danger">Offline</button>');
          }
          
      });
    }

  </script>
@endsection