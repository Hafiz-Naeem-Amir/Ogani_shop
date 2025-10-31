@extends('admin.layout.app')
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Content List</h3>
     <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#pageModal">
    <i class="fa fa-plus"></i> Content
</button>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Page</th>
                    <th>H1</th>
                    <th>H2</th>
                    <th>H3</th>
                    <th>P1</th>
                    <th>P2</th>
                    <th>Title</th>
                    <th>Design</th>
                    <th>Keyword</th>
                    <th>Content</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="pageModal" tabindex="-1" aria-labelledby="pageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="pageModalLabel">+Content</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="contentForm" enctype="multipart/form-data">
          @csrf
          <div class="row g-3">
            <div class="col-md-3">
               <label class="form-label">Page Type</label>
               <select class="form-control" id="page_id" name="page_id">
                   <option value="">-- Select Page --</option>
                   @foreach($pag as $page)
                       <option value="{{ $page->id }}">{{ $page->p_type_name }}</option>
                   @endforeach
               </select>
            </div>
            <div class="col-md-3"><label>H1</label><input type="text" class="form-control" id="h1" name="h1"></div>
            <div class="col-md-3"><label>H2</label><input type="text" class="form-control" id="h2" name="h2"></div>
            <div class="col-md-3"><label>H3</label><input type="text" class="form-control" id="h3" name="h3"></div>
            <div class="col-md-3"><label>P1</label><input type="text" class="form-control" id="p1" name="p1"></div>
            <div class="col-md-3"><label>P2</label><input type="text" class="form-control" id="p2" name="p2"></div>
            <div class="col-md-3"><label>Title</label><input type="text" class="form-control" id="title" name="title"></div>
            <div class="col-md-3"><label>Design</label><input type="text" class="form-control" id="design" name="design"></div>
            <div class="col-md-6"><label>Keyword</label><input type="text" class="form-control" id="keyword" name="keyword"></div>
            <div class="col-md-6"><label>Image</label><input type="file" class="form-control" id="image" name="image" accept="image/*"></div>
            <div class="col-md-12"><label>Content</label><textarea class="form-control" id="content" name="content"></textarea></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="saveContent" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(function () {
    // ---------------- DataTable Init ----------------
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ url('admin/contents-data') }}",
        columns: [
            {data:'id', name:'id'},
            {data:'page_name', name:'page.p_type_name'},
            {data:'h1', name:'h1'},
            {data:'h2', name:'h2', visible:false},
            {data:'h3', name:'h3', visible:false},
            {data:'p1', name:'p1'},
            {data:'p2', name:'p2', visible:false},
            {data:'title', name:'title'},
            {data:'design', name:'design', visible:false},
            {data:'keyword', name:'keyword', visible:false},
            {data:'content', name:'content', visible:false},
            {data:'image', name:'image', orderable:false, searchable:false},
            {data:'action', name:'action', orderable:false, searchable:false}
        ]
    });

    // ---------------- AJAX Setup ----------------
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    // ---------------- Save / Update Content ----------------
   $('#saveContent').off('click').on('click', function(e){
    e.preventDefault();
    let id = $(this).data('id'); // for update
    let url = id ? `/admin/contents/${id}` : "{{ url('admin/contents-store') }}";

    let formData = new FormData($('#contentForm')[0]);

    if(id){
        formData.append('_method', 'PUT'); // Laravel method spoofing
    }

    $.ajax({
        url: url,
        type: 'POST', // always POST when using FormData
        data: formData,
        contentType: false,
        processData: false,
        success:function(res){
            $('#contentForm')[0].reset();
            $('#pageModal').modal('hide');
            $('#saveContent').removeData('id');
            $('#existingImage').remove();
            $('#dataTable').DataTable().ajax.reload();

            Swal.fire({
                title: 'Success!',
                text: res.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        },
        error:function(err){
            let errors = err.responseJSON?.errors || {};
            let msg = '';
            $.each(errors,function(k,v){ msg += v+"\n"; });
            Swal.fire({ title:'Error!', text: msg, icon:'error', confirmButtonText:'OK' });
        }
    });
});

    // ---------------- Edit Content ----------------
    $(document).on('click', '.editBtn', function(e){
        e.preventDefault();
        let id = $(this).data('id');

        $.get(`/admin/contents/${id}/edit`, function(res){
            $('#pageModal').modal('show');
            $('#page_id').val(res.page_id);
            $('#h1').val(res.h1);
            $('#h2').val(res.h2);
            $('#h3').val(res.h3);
            $('#p1').val(res.p1);
            $('#p2').val(res.p2);
            $('#title').val(res.title);
            $('#design').val(res.design);
            $('#keyword').val(res.keyword);
            $('#content').val(res.content);

            if(res.image){
                $('#existingImage').remove();
                $('#image').after(`<img id="existingImage" src="/${res.image}" width="100" class="mt-2"/>`);
            }

            $('#saveContent').data('id', id);

            Swal.fire({
                icon: 'info',
                title: 'Edit Content',
                text: 'Content loaded into form',
                timer: 1500,
                showConfirmButton: false
            });
        }).fail(function(err){
            Swal.fire({ icon:'error', title:'Error', text:'Something went wrong!', confirmButtonText:'OK' });
        });
    });

    // ---------------- Delete Content ----------------
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
                    url: `{{ url('admin/content') }}/` + id,
                    type: 'DELETE',
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response){
                        table.ajax.reload(null, false);
                        Swal.fire({ title:'Deleted!', text: response.message, icon:'success', timer:2000, showConfirmButton:false });
                    },
                    error: function(err){
                        Swal.fire({ title:'Error!', text: err.responseJSON?.message || 'Something went wrong!', icon:'error', confirmButtonText:'OK' });
                    }
                });
            }
        });
    });
});

</script>
@endsection
