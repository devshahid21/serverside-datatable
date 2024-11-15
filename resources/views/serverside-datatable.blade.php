<!-- resources/views/users.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Table</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="{{ asset('datatables.css') }}" rel="stylesheet">

 
</head>
<body class="bg-light">
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <div class="container my-5">
        <h1 class="text-3xl font-bold mb-4">User List</h1>
 
        <table class="table table-bordered table-striped" id="datatable">
            <thead class="thead-light">
                <tr>
                <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Bootstrap JS and Popper.js (optional, for tooltips and modals) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('datatables.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('serverside-data') }}",
                    type: 'GET'  
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'created_at', name: 'created_at' },
                
                    // { data: 'action', name: 'action', orderable: false, searchable: false, defaultContent: '<button class="btn btn-primary">Edit</button>' }
                

                    {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">Delete</button>
                        `;
                    }
                }

                ],
                order: [[0, "asc"]]
            });
        });

         // Handle Edit Button Click
         $('#datatable').on('click', '.edit-btn', function() {
            let userId = $(this).data('id');
            // Call function to handle edit
            editUser(userId);
        });

        // Handle Delete Button Click
        $('#datatable').on('click', '.delete-btn', function() {
            let userId = $(this).data('id');
            // Call function to handle delete
            deleteUser(userId);
        });

    function editUser(id) {
        // Open edit form here (you can use a modal or redirect to an edit page)
        window.location.href = `/users/edit/${id}`;
    }

    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: `/users/delete/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.message);
                    $('#datatable').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert('Failed to delete the user.');
                }
            });
        }
    }

    </script>
</body>
</html>
