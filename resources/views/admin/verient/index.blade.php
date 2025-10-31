@extends('admin.layout.app')
@section('content')
<!-- Trigger Button -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Variants List</h3>
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#variantModal">
            <i class="fa fa-plus"></i> Variants
        </button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="variantModal" tabindex="-1" aria-labelledby="variantModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="variantModalLabel">Add  Variants</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="productForm">
            @csrf
            <input type="hidden" id="variant_id" name="variant_id">
            <!-- Product Name -->
            <div class="mb-3">
                <input type="text" id="product_name" name="name" class="form-control" placeholder="Varient Name" required>
            </div>

           <!-- Variants Fields -->
<div id="variantFields"></div>

<!-- Buttons -->
<button type="button" id="addField" class="btn btn-success btn-sm">+</button>

            <button type="button" id="removeField" class="btn btn-danger btn-sm">-</button>

           <div class="modal-footer">
               <!-- Submit -->
               <button type="submit" class="btn btn-primary mt-2">Save Product</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="card-body">
    <table id="dataTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>VALUES</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
$(function () {
   var table = $('#dataTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: "{{ url('admin/-vareint-table') }}",
    columns: [
        { data: 'id', name: 'id' },
        { data: 'name', name: 'name' },
        {
            data: 'values',
            name: 'values',
         render: function(data){
         const colors = ['primary','success','warning','danger','info','secondary'];
         if (Array.isArray(data)) {
         let badges = '';
         for (let i = 0; i < data.length; i++) {
            let color = colors[i % colors.length]; // repeat hone par colors cycle karenge
            badges += `<span class="badge bg-${color} me-1">${data[i]}</span>`;
        }
        return badges;
    }

    // Agar single value hai
    return `<span class="badge bg-primary">${data}</span>`;
    }


         },
         {
            data: 'id',
            name: 'action',
            orderable: false,
            searchable: false,
            render: function(data){
                return `
                    <button class="btn btn-sm btn-primary editBtn" data-id="${data}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${data}">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
            }
        }
    ]
});


    // Add/Remove variant inputs
// Possible placeholder list
const placeholders = ["Color", "Size", "Brand", "Model", "Type"];

$("#addField").click(function () {
    // total fields count
    let count = $("#variantFields .variantRow").length;

    // select placeholder according to index, or fallback
    let placeholder = placeholders[count] || "Custom";

    // create row
    let row = `
        <div class="variantRow mb-2 d-flex align-items-center">
            <input type="text" name="values[]" class="form-control mb-1 me-2"
                   placeholder="${placeholder}" required>
            <button type="button" class="btn btn-danger btn-sm removeField">
                <i class="bi bi-trash"></i>
            </button>
        </div>`;

    $("#variantFields").append(row);
});

// Delete specific field
$(document).on("click", ".removeField", function () {
    $(this).closest(".variantRow").remove();
});

// Delete specific variant row
$(document).on("click", ".removeField", function () {
    $(this).closest(".variantRow").remove();
});
// remove
    $("#removeField").click(function () {
        $("#variantFields .variantRow:last").remove();
    });

    // Submit form via AJAX
    $("#productForm").submit(function(e){
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('admin.verient.store') }}",
            type: "POST",
            data: formData,
            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                // $("#variantFields").empty();
                $("#product_name").val('');
                $("#variant_id").val('');
                $('#variantModal').modal('hide');
                $('#variantFields').empty();
                table.ajax.reload();
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    });


// Edit button click
$(document).on('click', '.editBtn', function() {
    let id = $(this).data('id');

    $.ajax({
        url: '/admin/verient/' + id,
        type: 'GET',
        success: function(res) {
            console.log(res); // debug ke liye

            $('#variant_id').val(res.id);

            $('#product_name').val(res.name);


            $('#variantFields').empty();


            if (Array.isArray(res.values)) {
                res.values.forEach(val => {
                    let row = `
                    <div class="variantRow mb-2 d-flex align-items-center">
                        <input type="text" name="values[]" class="form-control mb-1 me-2"
                            placeholder="${val}" required>
                        <button type="button" class="btn btn-primary btn-sm removeField">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>`;
                  $('#variantFields').append(row);
                });
            }


            $('#variantModal').modal('show');
        }
    });
});
//  update

 // Submit form via AJAX
    $("#productForm").submit(function(e){
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('admin.verient.update') }}",
            type: "POST",
            data: formData,
            success: function(response){
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                // $("#variantFields").empty();
                $("#product_name").val('');
                $("#variant_id").val('');
                $('#variantModal').modal('hide');
                table.ajax.reload();
            },
            error: function(xhr){
                console.log(xhr.responseText);
            }
        });
    });
$(document).on('click', '.deleteBtn', function() {
    let id = $(this).data('id');

    if (confirm('Are you sure to delete this variant?')) {
        $.ajax({
            url: '/admin/verient/delete/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                Swal.fire('Deleted!', res.success, 'success');
                table.ajax.reload();
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }
});


});
</script>
@endsection
