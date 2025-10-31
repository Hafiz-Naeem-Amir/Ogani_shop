@extends('admin.layout.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Page List</h3>
        <button type="button" class="btn btn-primary float-end" id="addPageBtn">
            <i class="fa fa-plus"></i>
            Add Page
        </button>
    </div>
   <div class="card-body">
      <table id="dataTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Page Type Name</th>
                <th>Page Name</th>
                <th>Page Slug</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="pageModal" tabindex="-1" aria-labelledby="pageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pageModalLabel">Add / Edit Page</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
          <form id="pageForm">
            @csrf
              <div class="mb-3">
                  <label class="form-label">Page Type Name</label>
                  <input type="text" class="form-control" id="p_type_name" required>
              </div>
              {{-- <div class="mb-3">
                  <label class="form-label">Select Page</label>
                  <select class="form-control" id="page_id">
                      <option value="">-- Select Page --</option>
                  </select>
                 </div> --}}
              <div class="mb-3">
                  <label class="form-label">Page Name</label>
                  <input type="text" class="form-control" id="p_name" required>
              </div>
              <div class="mb-3">
                  <label class="form-label">Page Slug</label>
                  <input type="text" class="form-control" id="p_slug" required>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="savePage" class="btn btn-primary">Add Page</button>
        <button type="button" id="updatePage" class="btn btn-success" style="display:none;">Update Page</button>
      </div>
    </div>
  </div>
</div>

<script>
$(function () {
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/pages-data') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'p_type_name', name: 'p_type_name' },
            { data: 'p_name', name: 'p_name' },
            { data: 'p_slug', name: 'p_slug' },
            {
                data: 'id',
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data){
                    return `
                        <button class="btn btn-sm btn-primary editBtn" data-id="${data}"> <i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="${data}"> <i class="fas fa-trash"></i></button>
                    `;
                }
            }
        ]
    });

    // Add Page Button Click
    $('#addPageBtn').click(function(){
        $('#pageModal').modal('show');
        $('#pageForm')[0].reset();
        $('#savePage').show();
        $('#updatePage').hide();
        $('#savePage').removeData('id');
    });

    // Add Page
    $('#savePage').click(function(){
    $.ajax({
        url: "{{ route('admin.pages.store') }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            p_type_name: $('#p_type_name').val(),
            p_name: $('#p_name').val(),
            p_slug: $('#p_slug').val()
        },
        success: function(response){
            // 1️⃣ Modal hide
            $('#pageModal').modal('hide');

            // 2️⃣ Form reset
            $('#pageForm')[0].reset();

            // 3️⃣ DataTable reload
            table.ajax.reload();

            // 4️⃣ SweetAlert success
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });
        },
        error: function(xhr){
            // Optional: SweetAlert error
            let res;
            try {
                res = JSON.parse(xhr.responseText);
            } catch(e) {
                res = { error: 'Something went wrong!' };
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res.error,
                showConfirmButton: true
            });
        }
    });
});

   // ---------------- Edit Page ----------------
$(document).on('click', '.editBtn', function(){
    let id = $(this).data('id');

    $.ajax({
        url: `/admin/pages/${id}/edit`,
        type: 'GET',
        success: function(data){
            // 1️⃣ Modal show
            $('#pageModal').modal('show');

            // 2️⃣ Form populate
            $('#p_type_name').val(data.p_type_name);
            $('#p_name').val(data.p_name);
            $('#p_slug').val(data.p_slug);

            // 3️⃣ Buttons toggle
            $('#savePage').hide();
            $('#updatePage').show().data('id', id);

            // 4️⃣ Optional: SweetAlert info
            Swal.fire({
                icon: 'info',
                title: 'Edit Page',
                text: 'Page data loaded into form',
                timer: 1500,
                showConfirmButton: false
            });
        },
        error: function(xhr){
            let res;
            try {
                res = JSON.parse(xhr.responseText);
            } catch(e){
                res = { error: 'Something went wrong!' };
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res.error,
                showConfirmButton: true
            });
        }
    });
});


   // ---------------- Update Page ----------------
$('#updatePage').click(function(){
    let id = $(this).data('id');

    $.ajax({
        url: `/admin/pages/${id}`,
        type: 'PUT',
        data: {
            _token: "{{ csrf_token() }}",
            p_type_name: $('#p_type_name').val(),
            p_name: $('#p_name').val(),
            p_slug: $('#p_slug').val()
        },
        success: function(response){
            // 1️⃣ Modal hide
            $('#pageModal').modal('hide');

            // 2️⃣ Form reset
            $('#pageForm')[0].reset();

            // 3️⃣ Buttons toggle
            $('#updatePage').removeData('id').hide();
            $('#savePage').show();

            // 4️⃣ DataTable reload
            table.ajax.reload();

            // 5️⃣ SweetAlert success
            Swal.fire({
                icon: 'success',
                title: 'Updated',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });
        },
        error: function(xhr){
            let res;
            try {
                res = JSON.parse(xhr.responseText);
            } catch(e){
                res = { error: 'Something went wrong!' };
            }

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res.error,
                showConfirmButton: true
            });
        }
    });
});

// ---------------- Delete Page ----------------
$(document).on('click', '.deleteBtn', function(){
    let id = $(this).data('id');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if(result.isConfirmed){
            $.ajax({
                url: `/admin/pages/${id}`,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function(response){
                    table.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr){
                    let res;
                    try {
                        res = JSON.parse(xhr.responseText);
                    } catch(e){
                        res = { error: 'Something went wrong!' };
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.error,
                        showConfirmButton: true
                    });
                }
            });
        }
    });
});

});
</script>
@endsection
