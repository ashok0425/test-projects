@extends('frontend.layout.master')
@section('content')
    
<div class="container mt-4">
    {{-- add products section start --}}
    <div class="row">
        <div class="col-md-6 offset-md-3">
          <div class="alert d-none"></div>

            <div class="card">
              <div class="card-header bg-dark text-light">
                <h5 class="card-title product_card_title">Create New Product</h5>
              </div>
              <div class="card-body">
                <form action="" method="POST" id="productForm">
                     @csrf
                     <input type="hidden" name="index">
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
                <button class="btn btn-primary" id="submitBtn" name="submit">Save</button>
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
      let alert=document.querySelector('.alert')

      loadproduct();

        function loadproduct() {
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    // parsing jso response 
       let res=JSON.parse(this.responseText)
       let data='';
    //    mapping the response 
       res.forEach((element,index) => {
        data+=`<tr><td>${element.name}</td><td>${element.qty}</td><td>${element.price}</td><td>${new Date(element.created_at).toLocaleDateString()}</td><td>${element.price*element.qty}</td><td><button class="btn btn-primary btn-sm EditBtn" data-id="${index}" onclick="EditProduct(this)">Edit</button></td></tr>`
       });
    document.getElementById("tbody").innerHTML = data;
    }

  xhttp.open("GET", "{{route('product.index')}}", true);
  xhttp.send();
}


let form = document.querySelector("#productForm");
form.addEventListener("submit", function(event) {
event.preventDefault();
let elements=form.elements;
let _token=document.querySelector("input[name='_token']").value;
let array={_token:_token,name:form.elements.namedItem('name').value,price:form.elements.namedItem('price').value,qty:form.elements.namedItem('qty').value,index:form.elements.namedItem('index').value};
// ajax call 
const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    // parsing jso response 
    let res=JSON.parse(this.responseText)
    if(res.success){
      
      alert.classList.add('alert-success')
      alert.classList.remove('d-none')
      alert.classList.remove('alert-danger')
      alert.innerHTML=res.message
      loadproduct();
      form.elements.namedItem('submit').innerHTML='save';
    document.querySelector('.product_card_title').innerHTML="Create New Product";
    form.reset()
    }else{
      alert.classList.add('alert-danger')
      alert.classList.remove('d-none')
      alert.classList.remove('alert-success')
      alert.innerHTML=res.message
    }
   
}
if (form.elements.namedItem('submit').innerHTML=='update') {
  xhttp.open("PATCH", "/product/update");
}else{
  xhttp.open("POST", "{{route('product.store')}}");

}
  xhttp.setRequestHeader("Content-type","application/json");
  xhttp.send(JSON.stringify(array));

})

//edit produt 
function EditProduct(element){
let id=element.getAttribute('data-id');
const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    let res=JSON.parse(this.responseText)

if (res.success) {
    form.elements.namedItem('name').value=res.data.name
    form.elements.namedItem('price').value=res.data.price
    form.elements.namedItem('qty').value=res.data.qty;
    form.elements.namedItem('index').value=id;
    form.elements.namedItem('submit').innerHTML='update';
    document.querySelector('.product_card_title').innerHTML="Edit Product"
}else{
     alert.classList.add('alert-danger')
      alert.classList.remove('d-none')
      alert.classList.remove('alert-success')
      alert.innerHTML=res.message
}

  }
xhttp.open("GET", `/product/${id}/edit`);
  xhttp.setRequestHeader("Content-type","application/json");
  xhttp.send();
}

    </script>
@endpush