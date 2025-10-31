<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-end mb-3 pe-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
        +Category
    </button>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="categoryModalLabel">Add / Edit Category</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="categoryForm" enctype="multipart/form-data">
            <input type="hidden" id="category_id" name="category_id">
            <?php echo csrf_field(); ?>

          <!-- Parent Dropdown -->
          <div class="mb-3" id="parentCategoryDiv">
              <label for="parent_id" class="form-label">Select Parent Category</label>
              <select name="parent_id" id="parent_id" class="form-control">
                  <option value="">-- Select Parent --</option>
                  <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
          </div>

          <!-- Category Name -->
          <div class="mb-3">
              <label for="name" class="form-label">Category Name</label>
              <input type="text" name="name" id="name" class="form-control" required>
          </div>

          <!-- Is Parent Checkbox -->
          <div class="form-check mb-3" id="isParentDiv">
              <input type="hidden" name="is_parent" value="0">
              <input type="checkbox" name="is_parent" id="is_parent" class="form-check-input" value="1">
              <label for="is_parent" class="form-check-label">Is Parent Category?</label>
          </div>

          <!-- Image -->
          <div class="col-md-12">
              <label class="form-label">Image</label>
              <input type="file" class="form-control" id="image" name="image">
              <div id="previewImages" style="margin-top:10px;"></div>
          </div>

          <!-- Status -->
          <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <select name="status" id="status" class="form-control">
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
              </select>
          </div>
        </form>
      </div>

      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" id="saveCategory" class="btn btn-primary">Save Category</button>
          <button type="button" id="updateCategory" class="btn btn-success d-none">Update Category</button>
      </div>
    </div>
  </div>
</div>

<div class="card-body">
  <table id="dataTable" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Parent ID</th>
            <th>Is Parent</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
  </table>
</div>

<script>
$(document).ready(function () {

    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "<?php echo e(url('admin/category-data')); ?>",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'parent_id', name: 'parent_id' },
            { data: 'is_parent', name: 'is_parent' },
            {
                data: 'image',
                name: 'image',
                render: function(data) {
                    return data
                        ? `<img src="/uploads/categories/${data}" width="60" height="60" style="object-fit:cover; border-radius:8px;">`
                        : `<span class="text-muted">No Image</span>`;
                }
            },
            { data: 'status', name: 'status' },
            {
                data: 'id',
                render: function(data) {
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

    // hide/show parent logic
    $('#is_parent').on('change', function () {
        $('#parentCategoryDiv').toggle(!$(this).is(':checked'));
        if ($(this).is(':checked')) $('#parent_id').val('');
    });

    $('#parent_id').on('change', function () {
        $('#isParentDiv').toggle(!$(this).val());
    });

    // ------------------ Store Category ------------------
    $('#saveCategory').on('click', function(e) {
        e.preventDefault();
        var formData = new FormData($('#categoryForm')[0]);

        $.ajax({
            url: "<?php echo e(route('admin.category.store')); ?>",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire(
                    { icon: 'success',
                     title: 'Success',
                     text: response.success,
                     timer: 2000,
                     showConfirmButton: false
                    });
                $('#categoryModal').modal('hide');
                $('#dataTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                Swal.fire({ icon: 'error',
                 title: 'Error',
                  text: 'Something went wrong!' });
            }
        });
    });

    // ------------------ Edit Category ------------------
    $(document).on('click', '.editBtn', function() {
        let id = $(this).data('id');

        $.ajax({
            url: "/admin/category/edit/" + id,
            type: 'GET',
            success: function(data) {
                $('#categoryModal').modal('show');
                $('#category_id').val(data.id);
                $('#name').val(data.name);
                $('#status').val(data.status);

                if (data.image) {
                    $('#previewImages').html(`
                        <div id="imageContainer" style="position: relative; display: inline-block;">
                            <img src="/uploads/categories/${data.image}" width="80" height="80" style="object-fit:cover; border-radius:8px; margin-top:10px;">
                            <span id="deletePreviewImage" style="position:absolute; top:-8px; right:-8px; background:red; color:white;
                                   width:20px; height:20px; border-radius:50%; text-align:center; line-height:20px; cursor:pointer; font-weight:bold;">×</span>
                        </div>
                    `);
                } else {
                    $('#previewImages').html(`<span class="text-muted">No Image</span>`);
                }

                if (data.is_parent == 1) {
                    $('#is_parent').prop('checked', true);
                    $('#parentCategoryDiv').hide();
                } else {
                    $('#is_parent').prop('checked', false);
                    $('#parentCategoryDiv').show();
                    $('#parent_id').val(data.parent_id);
                }

                // Switch button to Update mode
                $('#saveCategory').addClass('d-none');
                $('#updateCategory').removeClass('d-none');
            }
        });
    });

    // ❌ Delete image preview
    $(document).on('click', '#deletePreviewImage', function() {
        $('#imageContainer').remove();
        $('#previewImages').html(`<span class="text-muted">No Image</span>`);
    });

    // ------------------ Update Category ------------------
    $('#updateCategory').on('click', function(e) {
        e.preventDefault();

        let id = $('#category_id').val();
        let formData = new FormData($('#categoryForm')[0]);
        formData.append('_method', 'POST');

        if ($('#imageContainer').length === 0 && !$('#image')[0].files.length) {
            formData.append('remove_image', 1);
        }

        $.ajax({
            url: "/admin/category/update/" + id,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                Swal.fire({ icon: 'success', title: 'Updated!', text: response.success, timer: 2000, showConfirmButton: false });
                $('#categoryModal').modal('hide');
                $('#dataTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!' });
            }
        });
    });

    // ------------------ Delete Category ------------------
    $(document).on('click', '.deleteBtn', function() {
        let id = $(this).data('id');
        let _token = $('input[name="_token"]').val();

        Swal.fire({
            title: 'Are you sure?',
            text: "This category will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/admin/category/delete/" + id,
                    type: "DELETE",
                    data: { _token: _token },
                    success: function(response) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: response.success, timer: 2000, showConfirmButton: false });
                        $('#dataTable').DataTable().ajax.reload();
                    }
                });
            }
        });
    });

    // ------------------ Modal Reset on Close ------------------
    $('#categoryModal').on('hidden.bs.modal', function () {
        $('#categoryForm')[0].reset();
        $('#previewImages').html('');
        $('#saveCategory').removeClass('d-none');
        $('#updateCategory').addClass('d-none');
    });

});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laravel Internship projects\ogani-shop\resources\views/admin/category/index.blade.php ENDPATH**/ ?>