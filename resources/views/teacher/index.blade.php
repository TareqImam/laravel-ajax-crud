<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>Laravel & Ajax CRUD</title>
    </head>

    <body>
        <div style="padding: 30px;"></div>
        <div class="container">
            <h2 style="color: red;">
                <marquee behavior="" direction="">Laravel & Ajax CRUD</marquee>
            </h2>
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">Teacher List</div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thread>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Title</th>
                                        <th>Institute</th>
                                        <th>Action</th>
                                    </tr>
                                </thread>
                                <tbody>
                                    {{-- <tr>
                                        <td>1</td>
                                        <td>Jhon</td>
                                        <td>Teacher</td>
                                        <td>MMA</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary mr-2">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    </tr>  --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <span id="addT">Add new Teacher</span>
                            <span id="updateT">Update Teacher</span>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" aria-describedby="emailHelp"
                                    placeholder="Enter Name">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" aria-describedby="emailHelp"
                                    placeholder="Enter Title">
                                <span class="text-danger" id="titleError"></span>
                            </div>
                            <div class="form-group">
                                <label for="institute">Institute</label>
                                <input type="text" class="form-control" id="institute" aria-describedby="emailHelp"
                                    placeholder="Enter Institute">
                                <span class="text-danger" id="instituteError"></span>
                            </div>
                            <input type="hidden" id="id">
                            <button type="submit" onclick="addData()" id="addButton"
                                class="btn btn-primary mt-2">Add</button>
                            <button type="submit" id="updateButton" onclick="updateData()"
                                class="btn btn-warning mt-2">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script>
            $( '#addT' ).show();
            $( '#updateT' ).hide();
            $( '#addButton' ).show();
            $( '#updateButton' ).hide();

            $.ajaxSetup( {
                headers: {
                    'X-CSRF-TOKEN': $( 'meta[name="csrf-token"]' ).attr( 'content' )
                }
            } )

            function clearData() {
                $( '#name' ).val( '' );
                $( '#title' ).val( '' );
                $( '#institute' ).val( '' );
                $( '#nameError' ).text( '' );
                $( '#titleError' ).text( '' );
                $( '#instituteError' ).text( '' );
            }

            function allData() {
                $.ajax( {
                    type: "GET",
                    dataType: 'json',
                    url: "teacher/all",
                    success: function ( response ) {
                        var data = ""
                        $.each( response, function ( key, value ) {
                            data = data + "<tr>"
                            data = data + "<td>" + value.id + "</td>"
                            data = data + "<td>" + value.name + "</td>"
                            data = data + "<td>" + value.title + "</td>"
                            data = data + "<td>" + value.institute + "</td>"
                            data = data + "<td>"
                            data = data +
                                "<button class='btn btn-sm btn-primary mr-2' onclick='editData(" +
                                value.id + ")'>Edit</button>"
                            data = data +
                                "<button class='btn btn-sm btn-danger' onclick='deleteData(" + value
                                .id + ")'>Delete</button>"
                            data = data + "</td>"
                            data = data + "</tr>"
                        } )
                        $( 'tbody' ).html( data );
                    }
                } )
            }
            allData();

            function addData() {
                var name = $( '#name' ).val();
                var title = $( '#title' ).val();
                var institute = $( '#institute' ).val();

                $.ajax( {
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        name: name,
                        title: title,
                        institute: institute
                    },
                    url: "teacher/store",
                    success: function ( data ) {
                        clearData();
                        allData();

                        const msg = Swal.mixin( {
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        } )

                        msg.fire( {
                            type: 'success',
                            title: 'User created',
                        } )
                    },
                    error: function ( error ) {
                        $( '#nameError' ).text( error.responseJSON.errors.name );
                        $( '#titleError' ).text( error.responseJSON.errors.title );
                        $( '#instituteError' ).text( error.responseJSON.errors.institute );
                    }
                } )
            }

            function editData( id ) {
                $.ajax( {
                    type: "GET",
                    dataType: "json",
                    url: "teacher/edit/" + id,
                    success: function ( data ) {
                        $( '#addT' ).hide();
                        $( '#updateT' ).show();
                        $( '#addButton' ).hide();
                        $( '#updateButton' ).show();

                        $( '#id' ).val( data.id );
                        $( '#name' ).val( data.name );
                        $( '#title' ).val( data.title );
                        $( '#institute' ).val( data.institute );
                    }
                } )
            }

            function updateData() {
                var id = $( '#id' ).val();
                var name = $( '#name' ).val();
                var title = $( '#title' ).val();
                var institute = $( '#institute' ).val();

                $.ajax( {
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        name: name,
                        title: title,
                        institute: institute
                    },
                    url: "teacher/update/" + id,
                    success: function () {
                        $( '#addT' ).show();
                        $( '#updateT' ).hide();
                        $( '#addButton' ).show();
                        $( '#updateButton' ).hide();

                        clearData();
                        allData();

                        const msg = Swal.mixin( {
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        } )

                        msg.fire( {
                            type: 'success',
                            title: 'User updated',
                        } )
                    },
                    error: function ( error ) {
                        $( '#nameError' ).text( error.responseJSON.errors.name );
                        $( '#titleError' ).text( error.responseJSON.errors.title );
                        $( '#instituteError' ).text( error.responseJSON.errors.institute );
                    }
                } )
            }

            function deleteData( id ) {
                Swal.fire( {
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes'
                } ).then( ( result ) => {
                    if ( result.isConfirmed ) {
                        $.ajax( {
                            type: "GET",
                            dataType: "json",
                            url: "/teacher/delete/" + id,
                            success: function ( response ) {
                                $( '#add' ).show();
                                $( '#update' ).hide();
                                $( '#addT' ).show();
                                $( '#updateT' ).hide();

                                allData();
                                clearData();

                                const msg = Swal.mixin( {
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500
                                } )

                                msg.fire( {
                                    type: 'success',
                                    title: 'User deleted',
                                } )
                            }
                        } );
                    } else {
                        const msg = Swal.mixin( {
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        } )

                        msg.fire( {
                            type: 'success',
                            title: 'User not deleted',
                        } )
                    }
                } );

            }

        </script>
    </body>

</html>
