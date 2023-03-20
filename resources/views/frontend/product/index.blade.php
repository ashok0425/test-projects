@extends('frontend.layout.master')
@section('content')
    
<div class="container mt-4">
    {{-- add products section start --}}
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
              <div class="card-body">
                <form action="" method="POST" id="productForm">
@csrf
                    <div class="form-group mt-3">
                        <label for="name">Product Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="qty">Product Quantity</label>
                        <input type="text" name="qty" id="qty" class="form-control" required>
                    </div>

                    <div class="form-group mt-3 ">
                        <label for="price">Product Price/unit</label>
                        <input type="text" name="price" id="price" class="form-control" required>
                    </div>
            <div class="form-group text-end mt-3">
                <button class="btn btn-primary" id="submitBtn">Save</button>
            </div>
                </form>
              </div>
            </div>
        </div>
    </div>
    {{-- add products section eend --}}


      {{-- display products section start --}}
      <div class="row mt-2">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>Product List</h5>
                </div>
              <div class="card-body">
                <table class="table text-center table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>unit price</th>
                            <th>created At</th>
                            <th>Total Value</th>
                            <th>Action</th>


                        </tr>
                    </thead>
                    <tbody id="tbody">

                    </tbody>
                </table>
              </div>
            </div>
           
        </div>
    </div>
    {{-- display products section eend --}}
</div>
@endsection

@push('script')
    <script>
        function loadproduct() {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    // parsing jso response 
       let res=JSON.parse(this.responseText)
       let data='';
    //    mapping the response 
       res.forEach(element => {
        data+=`<tr><td>${element.name}</td><td>${element.qty}</td><td>${element.price}</td><td>${element.created_at}</td><td>${element.price*element.qty}</td><td>Edit</td></tr>`
       });
    document.getElementById("tbody").innerHTML = data;
    }

  xhttp.open("GET", "{{route('product.index')}}", true);
  xhttp.send();
}


loadproduct();

let form = document.querySelector("#productForm");
form.addEventListener("submit", function(event) {
event.preventDefault();
let elements=form.elements;
let _token=document.querySelector("input[name='_token']").value;
let array={_token:_token,name:form.elements.namedItem('name').value,price:form.elements.namedItem('price').value,qty:form.elements.namedItem('qty').value};
// ajax call 
const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    // parsing jso response 
    let res=JSON.parse(this.responseText)
    console.log(res);
// loadproduct();
}
  xhttp.open("POST", "{{route('product.store')}}");
  xhttp.setRequestHeader("Content-type","application/json");
  xhttp.send(JSON.stringify(array));
})
    </script>
@endpush