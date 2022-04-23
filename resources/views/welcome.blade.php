<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset("css/app.css")}}">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mt-5">
                <div class="card-body">
                    <form action="{{route('fruit.store')}}" id="fruitForm" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-3">
                                <input type="file" class="form-control" name="photo">
                            </div>

                            <div class="col-3">
                                <input type="text" class="form-control" name="name">

                            </div>

                            <div class="col-3">
                                <input type="number" class="form-control" name="price">

                            </div>

                            <div class="col-3">
                                <button class="btn btn-primary">
                                    <span class="spinner-border d-none spinner-border-sm btn-loader" role="status" aria-hidden="true"></span>
                                    Add Fruit
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Control</th>
                                <th>Created At</th>
                            </tr>
                        </thead>

                        <tbody id="row">
                        @foreach(\App\Models\Fruits::all() as $fruit)
                            <tr id="row{{$fruit->id}}">
                                <td>{{{$fruit->id}}}</td>
                                <td>
                                    <a class="my-link" href="{{asset('storage/photo/'.$fruit->photo)}}">
                                        <img src="{{asset('storage/thumbnail/'.$fruit->photo)}}" width="50" alt="image alt"/>
                                    </a>

                                    <img  alt="">
                                </td>
                                <td>{{$fruit->name}}</td>
                                <td>{{$fruit->price}}</td>
                                <td>
                                    <div class="btn-group">

                                        <button class="btn btn-outline-danger btn-sm" onclick="del({{$fruit->id}})">
                                            <i class="fas fa-trash fa-fw"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" onclick="edit({{$fruit->id}})">
                                            <i class="fas fa-pencil-alt fa-fw"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>{{$fruit->created_at->diffForHumans()}}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="editBox" tabindex="-1" aria-labelledby="editBoxLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBoxLabel">Edit Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="editForm" method="post">
                    @csrf
                    @method('put')

                    <img src="" class="w-50 d-block mx-auto " id="editImg" alt="">
                    <button type="button" class="btn btn-sm btn-primary camera">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" name="photo" id="editPhoto" class="form-control d-none">
                    <input type="text" name="name" id="editName"  class="form-control">
                    <input type="number" name="price" id="editPrice" class="form-control">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button  form="editForm" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</div>



<script src="{{asset('js/app.js')}}"> </script>

<script>
    let fruitForm =document.querySelector("#fruitForm")
    let btnLoaderUi=document.querySelector('.btn-loader');
    let editPhoto =  document.querySelector('#editPhoto');
    let currentModal =  new bootstrap.Modal(document.getElementById("editBox"),{backdrop : 'static'});


    fruitForm.addEventListener("submit",function (e){
        //loading Start


        e.preventDefault();

        btnLoaderUi.classList.toggle("d-none");

        let formData= new FormData(this);
        axios.post(fruitForm.getAttribute('action'),formData).then(function (response){



            let rows =document.getElementById('row');

           if(response.data.status == "success"){

               console.log(response.data);

               let info= response.data.info;

               let tr = document.createElement('tr');
               tr.setAttribute("id","row"+info.id);


               tr.classList.add("animate__animated","animate__slideInDown");

               tr.innerHTML = `
                    <td>${info.id}</td>
                    <td>
                        <a class="my-link" href="${info.original_photo}}">
                           <img src="${info.thumbnail}" width="50" alt="image alt"/>
                        </a>

                    </td>
                    <td>${info.name}</td>
                    <td>${info.price}</td>
                    <td>

                        <div class="class="btn-group"">
                            <button class="btn btn-outline-danger btn-sm" onclick="del(${info.id})">
                                <i class="fas fa-trash fa-fw"></i>
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="edit(${info.id})">
                                <i class="fas fa-pencil-alt fa-fw"></i>
                            </button>
                        </div>

                    </td>
                    <td>${info.time}</td>


               `

               rows.append(tr);

               fruitForm.reset();
           }else{
              Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'Something went wrong!',
                  footer: '<a href="">Why do I have this issue?</a>'
              })
           }

           //loading stop
            btnLoaderUi.classList.toggle("d-none");




        });

    });


    function del(id){
        axios.delete("fruit/"+id).then(function (response){
          if(response.data.status == "success"){
              const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: (toast) => {
                      toast.addEventListener('mouseenter', Swal.stopTimer)
                      toast.addEventListener('mouseleave', Swal.resumeTimer)
                  }
              })

              Toast.fire({
                  icon: 'success',
                  title: response.data.info,
              })

              document.getElementById('row'+id).remove();
          }
        })
    }

    function edit(id){
        axios.get("fruit/"+id).then(function (response){
            console.log(response.data);
            let info = response.data;

            document.getElementById("editName").value = info.name;
            document.getElementById("editPrice").value = info.price;

            document.getElementById("editForm").setAttribute("data-id",id);

            document.getElementById("editImg").src = info.original_photo;


            currentModal.show();
        })

    }

    document.getElementById("editForm").addEventListener("submit",function (e){
        e.preventDefault();

        let aa = new FormData(this);
        let id = this.getAttribute("data-id");

        axios.post("fruit/"+id,aa).then(function (response){

            // console.log(response.data)
            if(response.data.status == "success"){
                currentModal.hide();

                let info = response.data.info;


                let tr = document.getElementById('row'+id);

                tr.innerHTML = `
                    <td>${info.id}</td>
                    <td>
                        <a class="my-link" href="${info.original_photo}}">
                           <img src="${info.thumbnail}" width="50" alt="image alt"/>
                        </a>

                    </td>
                    <td>${info.name}</td>
                    <td>${info.price}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-outline-danger btn-sm" onclick="del(${info.id})">
                                <i class="fas fa-trash fa-fw"></i>
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="edit(${info.id})">
                                <i class="fas fa-pencil-alt fa-fw"></i>
                            </button>
                        </div>
                    </td>
                    <td>${info.time}</td>


               `


            }else{
                console.log(response.data)
            }

        })

        console.log("Hollo Update")
    })


    document.querySelector(".camera").addEventListener("click",function (){
        editPhoto.click();
    })

    editPhoto.addEventListener("change",function (){
        let currentFile = this.files[0];
        let fileReader =  new FileReader();

        fileReader.onload = function (e){
            document.getElementById("editImg").src = e.target.result;
        }

        fileReader.readAsDataURL(currentFile);

    })


    // new VenoBox({
    //     selctor: '.venobox'
    // });



    // let frutiForm=$("#fruitForm");
    // frutiForm.on('submit',function (e){
    //     e.preventDefault();
    //
    //     $.post($(this).attr('action'),$(this).serialize(),function (data){
    //        if (data.status == "success"){
    //            Swal.fire({
    //                icon: 'success',
    //                title: 'Oops...',
    //                text: 'Something went wrong!',
    //                footer: '<a href="">Why do I have this issue?</a>'
    //            })
    //        }else{
    //            Swal.fire({
    //                icon: 'error',
    //                title: 'Oops...',
    //                text: 'Something went wrong!',
    //                footer: '<a href="">Why do I have this issue?</a>'
    //            })
    //        }
    //     });
    //
    //
    //
    //
    //
    //     console.log("u Submit")
    // })
</script>
</body>
</html>
