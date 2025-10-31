<?php $__env->startSection('content'); ?>

<!-- Button to open modal -->


<div class="d-flex justify-content-end mb-3 pe-3" >
   <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">+ Add Product</button>
</div>
<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">+ Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form id="productForm" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <input type="hidden" id="product_id" name="product_id">

          <div class="row g-3">

           <div class="col-md-6">
  <label class="form-label">Title</label>
  <input type="text" class="form-control" placeholder="Enter Title" id="title" name="title" required>
</div>

<div class="col-md-6">
  <label class="form-label">Slug</label>
  <input type="text" class="form-control" placeholder="Enter Slug" id="slug" name="slug">
</div>


            <!-- Keyword -->
            <div class="col-md-6">
              <label class="form-label">Keyword</label>
              <input type="text" class="form-control" placeholder="Enter Name" id="keyword" name="keyword" required>
            </div>



              <!-- Price -->
            <div class="col-md-6">
              <label class="form-label">Price</label>
              <input type="text" class="form-control" placeholder="Enter Price" id="price" name="price" required>
            </div>
              <!-- Stock -->
            <div class="col-md-6">
              <label class="form-label">Stock</label>
              <input type="text" class="form-control" placeholder="Enter Stock" id="stock" name="stock" required>
            </div>
              <!-- Discount -->
            <div class="col-md-6">
              <label class="form-label">Discount</label>
              <input type="text" class="form-control" placeholder="Enter Discount" id="discount" name="discount" required>
            </div>
            <!-- Condition Salehold -->
            <div class="col-md-6">
            <label class="form-label">Condition Salehold</label>
            <select class="form-control" id="condition_salehold" name="condition_salehold" required>
                <option value="">-- Condition Salehold --</option>
                <option value="hot">Hot</option>
                <option value="brand">Brand</option>
                <option value="new">New</option>
                <option value="used">Used</option>
                <option value="refurbished">Refurbished</option>
            </select>
            </div>

           <!-- Category -->
            <div class="col-md-6">
            <label class="form-label">Category</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            </div>


            <!-- Image -->
            <div class="col-md-6">
              <label class="form-label">Image</label>
              <input type="file" class="form-control" id="image" name="images[]" multiple accept="image/*">
              <div id="previewImages" style="margin-top:10px;"></div>
            </div>



            <!-- Variant -->
            <div class="col-md-6">
              <label class="form-label">Variant</label>
              <select class="form-control" id="variant_id" name="variant_id" required>
                <option value="">-- Select Variant --</option>
                <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($variant->id); ?>"><?php echo e($variant->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>

            <!-- Variant Values -->
            <div class="col-md-6 mt-3">
              <label class="form-label">Variant Values</label>
              <select id="variant_values" name="variant_list[]" multiple="multiple" required style="width:100%">
                <!-- Options will be added dynamically -->
              </select>
            </div>
           <div id="variantFields" class="row"></div>
            <!-- Add Button -->
            <button type="button" id="addVariantBtn"
              class="btn btn-success btn-sm mt-2 px-2 py-1"
              style="font-size: 12px; width: 110px;">
              <i class="bi bi-plus-circle"></i> Add Variant
            </button>

            <!-- Meta Description -->
            <div class="col-md-12">
              <label class="form-label">Meta Description</label>
              <textarea class="form-control" id="meta_description" placeholder="Enter Meta Description"
                name="meta_description" rows="3" required></textarea>
            </div>

                    <!-- Content Description -->
            <div class="col-md-12">
            <label class="form-label">Content Description</label>
            <textarea class="form-control ckeditor" id="content_description"
                placeholder="Enter Content Description" name="content_description" rows="4" required></textarea>
            </div>

          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="saveProduct" class="btn btn-primary">Save Product</button>
      </div>

    </div>
  </div>
</div>

<!-- Fullscreen Slider Modal -->
<div class="modal fade" id="sliderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body position-relative">

        <!-- Main Image -->
        <div class="text-center mb-3 position-relative">
          <img id="mainSliderImage" src="" class="img-fluid" style="max-height:400px; object-fit:cover; border-radius:5px;">

          <!-- Prev/Next buttons -->
          <button id="prevImage" class="btn btn-dark position-absolute top-50 start-0 translate-middle-y" style="opacity:0.7;">&#10094;</button>
          <button id="nextImage" class="btn btn-dark position-absolute top-50 end-0 translate-middle-y" style="opacity:0.7;">&#10095;</button>
        </div>

        <!-- Thumbnails -->
        <div class="d-flex justify-content-center flex-wrap" id="thumbnailContainer"></div>

      </div>
      <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- DataTable -->
<div class="card mt-4">
  <div class="card-body">
    <table id="dataTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Keyword</th>
          <th>Slug</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Discount</th>
          <th>Meta Description</th>
          <th>Content Description</th>
          <th>Images</th>
          <th>Category</th>
          <th>Variant</th>
          <th>Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Script Section -->
<script>
$(document).ready(function () {

  // ==================== DataTable ====================
$('#dataTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "<?php echo e(url('admin/product-table')); ?>",
    columns: [
        { data: 'id', name: 'id' },
        { data: 'title', name: 'title' },
        { data: 'keyword', name: 'keyword' },
        { data: 'slug', name: 'slug' },
        { data: 'price', name: 'price' },
        { data: 'stock', name: 'stock' },
        { data: 'discount', name: 'discount' },
        { data: 'meta_description', name: 'meta_description', visible: false },
        { data: 'content_description', name: 'content_description', visible: false },
        { data: 'images', name: 'images' }, // now matches controller
        { data: 'category_name', name: 'category.name', visible: false },
        { data: 'variant_name', name: 'variant.name', visible: false },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
});



  // ==================== Select2 Initialization ====================
  $('#variant_values').select2({
    placeholder: "Select or Add Variant Values",
    allowClear: true,
    width: '100%',
    tags: true,
    tokenSeparators: [',', ' '],
    dropdownParent: $('#variant_values').parent()
  });

  // Load variant values via AJAX
  $('#variant_id').on('change', function () {
    let variantId = $(this).val();

    if(!variantId){
      $('#variant_values').empty().trigger('change');
      return;
    }

    $('#variant_values').html('<option>Loading...</option>').trigger('change');

    $.ajax({
      url: `/admin/get-variant-fields/${variantId}`,
      type: "GET",
      dataType: "json",
      success: function(values){
        $('#variant_values').empty();
        if(values.length === 0){
          $('#variant_values').html('<option disabled>No values found</option>').trigger('change');
          return;
        }
        values.forEach(function(val){
          let newOption = new Option(val, val, false, false);
          $('#variant_values').append(newOption);
        });
        $('#variant_values').trigger('change');
      },
      error: function(){
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to load variant values. Please try again.'
        });
      }
    });
  });

  // ==================== Add/Remove Variant Inputs ====================
$('#addVariantBtn').click(function(e) {
  e.preventDefault();

  // Variant block (HTML)
  let block = `
    <div class="variant-block row align-items-end mt-3 border p-2 rounded position-relative">

      <!-- Variant -->
      <div class="col-md-6">
        <label class="form-label">Variant</label>
        <select class="form-control variant_id" name="variant_id[]" required>
          <option value="">-- Select Variant --</option>
          <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($variant->id); ?>"><?php echo e($variant->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>

      <!-- Variant Values + Delete Button -->
      <div class="col-md-6 mt-3 d-flex align-items-center">
        <div class="flex-grow-1">
          <label class="form-label">Variant Values</label>
          <select class="form-control variant_values" name="variant_values[][]" multiple="multiple" required style="width:100%">
            <!-- Options will be added dynamically -->
          </select>
        </div>
        <button type="button" class="btn btn-danger btn-sm ms-2 mt-4 remove-variant" title="Remove">
          <i class="bi bi-x-circle"></i>
        </button>
      </div>

    </div>
  `;

  // Append new block first
  $('#variantFields').append(block);

  // Initialize Select2 for the new select
  $('#variantFields .variant_values').last().select2({
    placeholder: "Select or Add Variant Values",
    width: '100%',
    tags: true,
    tokenSeparators: [',', ' '],
    dropdownParent: $('#variantFields')
  });
});

// 🗑️ Delete variant block
$(document).on('click', '.remove-variant', function() {
  $(this).closest('.variant-block').remove();
});


// ================== Load Variant Values via AJAX ==================
$(document).on('change', '.variant_id', function() {
  let $block = $(this).closest('.variant-block');
  let variantId = $(this).val();
  let $valuesSelect = $block.find('.variant_values');

  if (!variantId) {
    $valuesSelect.empty().trigger('change');
    return;
  }

  $valuesSelect.html('<option>Loading...</option>').trigger('change');

  $.ajax({
    url: `/admin/get-variant-fields/${variantId}`,
    type: "GET",
    dataType: "json",
    success: function(values) {
      $valuesSelect.empty();
      if (values.length === 0) {
        $valuesSelect.html('<option disabled>No values found</option>').trigger('change');
        return;
      }
      values.forEach(function(val) {
        let newOption = new Option(val, val, false, false);
        $valuesSelect.append(newOption);
      });
      $valuesSelect.trigger('change');
    },
    error: function() {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to load variant values. Please try again.'
      });
    }
  });
});

    // auto fetching slug
         $('#title').on('input',function(){
    var title = $(this).val();
    if(!$('#slug').data('manual')){
        $.ajax({
            url: '/admin/generate-slug',
            type:'GET',
            data:{title : title},
            success : function(response) {
             $('#slug').val(response.slug);
            }
        })
    }s
 })
  // ==================== Save Product ====================




$('#saveProduct').on('click', function () {
    // Summernote content
    $('#content_description').val($('#content_description').summernote('code'));

    let productId = $('#product_id').val();
    let formData = new FormData($('#productForm')[0]);
    let url = productId ? "/admin/product/update" : "<?php echo e(route('admin.product.store')); ?>";

    if(productId) formData.append('product_id', productId);

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: productId ? 'Updated!' : 'Saved!',
                text: response.message || 'Product saved successfully!',
                confirmButtonText: 'OK'
            }).then(() => {
                $('#productModal').modal('hide');
                $('#productForm')[0].reset();
                $('#variantFields').html('');
                $('#previewImages').html('');
                $('#dataTable').DataTable().ajax.reload();
            });
        },
        error: function(xhr) {
            let msg = 'Something went wrong!';

            // Validation errors
            if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors){
                msg = '';
                $.each(xhr.responseJSON.errors, function(key, errors){
                    msg += errors.join('\n') + '\n';
                });
            }
            // Server errors (optional)
            else if(xhr.responseJSON && xhr.responseJSON.message){
                msg = xhr.responseJSON.message;
            }

            console.log(xhr); // Debugging: console me full response dekh sakte ho

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg.trim()
            });
        }
    });
})


// ==================== Edit Product ====================
$(document).on('click', '.editBtn', function() {
    let productId = $(this).data('id');

    $.ajax({
        url: "/admin/product/edit/" + productId,
        type: "GET",
        success: function(response) {
            if(response.success) {
                let product = response.data;

                // ---------------- Populate all fields ----------------
                $('#product_id').val(product.id);
                $('#title').val(product.title);
                $('#keyword').val(product.keyword);
                $('#slug').val(product.slug);
                $('#price').val(product.price);
                $('#stock').val(product.stock);
                $('#discount').val(product.discount);
                $('#category_id').val(product.category_id);
                $('#variant_id').val(product.variant_id);
                $('#variantFields').val(product.variant_list);
                $('#meta_description').val(product.meta_description);

                // Summernote editor for content_description
                $('#content_description').summernote('code', product.content_description);

                // ---------------- Preview Images ----------------
                $('#previewImages').html('');
                if(response.images && response.images.length > 0){
                    response.images.forEach(function(img){
                        $('#previewImages').append(`
                            <div class="position-relative d-inline-block me-2 mb-2" style="width:70px; height:70px;">
                                <img src="/uploads/products/${img}" class="img-thumbnail preview-img" style="width:100%; height:100%; object-fit:cover; border-radius:5px;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 removeImage" data-img="${img}"
                                    style="padding:0; width:20px; height:20px; line-height:16px; font-size:14px; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                                        &times;
                                </button>
                            </div>
                        `);
                    });
                }

                // ---------------- Show Modal ----------------
                $('#productModal').modal('show');
            }
        },
        error: function() {
            Swal.fire('Error', 'Something went wrong while fetching the product.', 'error');
        }
    });
 });

 // ---------------- Slider Functionality ----------------
 let imagesArray = [];
 let currentIndex = 0;

 // Click on any preview image
 $(document).on('click', '.preview-img', function() {
    let clickedSrc = $(this).attr('src');

    // Build images array from current previews
    imagesArray = [];
    $('#previewImages img').each(function(){
        imagesArray.push($(this).attr('src'));
    });

    currentIndex = imagesArray.indexOf(clickedSrc);

    // Show main image & thumbnails
    updateMainImage();
    populateThumbnails();

    // Show slider modal
    $('#productModal').modal('hide');
    $('#sliderModal').modal('show');
 });

 function updateMainImage() {
    $('#mainSliderImage').attr('src', imagesArray[currentIndex]);
    $('.thumbnailImage').css('border','2px solid #ddd');
    $(`.thumbnailImage[data-index="${currentIndex}"]`).css('border','2px solid #007bff');
 }

 function populateThumbnails() {
    $('#thumbnailContainer').html('');
    imagesArray.forEach(function(src, i){
        $('#thumbnailContainer').append(`
            <img src="${src}" class="img-thumbnail m-1 thumbnailImage" data-index="${i}" style="width:70px; height:70px; object-fit:cover; cursor:pointer; border:2px solid ${i === currentIndex ? '#007bff' : '#ddd'};">
        `);
    });

    $('.thumbnailImage').off('click').on('click', function() {
        currentIndex = $(this).data('index');
        updateMainImage();
    });
 }

 // Next/Prev buttons
 $('#nextImage').off('click').on('click', function(){
    currentIndex = (currentIndex + 1) % imagesArray.length;
    updateMainImage();
  });
 $('#prevImage').off('click').on('click', function(){
    currentIndex = (currentIndex - 1 + imagesArray.length) % imagesArray.length;
    updateMainImage();
 });

 // Slider modal close → show product modal again
 $('#sliderModal').off('hidden.bs.modal').on('hidden.bs.modal', function () {
    $('#productModal').modal('show');
 });

 // ---------------- Remove Image ----------------
 $(document).on('click', '.removeImage', function() {
    let imgName = $(this).data('img');
    $(this).parent().remove(); // remove from UI

    // Optional: remove from database via AJAX
    $.ajax({
        url: '/admin/product/image-delete',
        type: 'POST',
        data: { product_id: $('#product_id').val(), image: imgName, _token: '<?php echo e(csrf_token()); ?>' },
        success: function(res) {
            if(res.success){
                console.log(imgName + ' deleted from server');
            }
        }
    });
});






  // ==================== Delete Product ====================
  $(document).on('click', '.deleteBtn', function(){
    let productId = $(this).data('id');

    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if(result.isConfirmed){
        $.ajax({
          url: '/admin/product/' + productId,
          type: 'DELETE',
          data: { _token: '<?php echo e(csrf_token()); ?>' },
          success: function(response){
            if(response.success){
              Swal.fire('Deleted!', response.message, 'success');
              $('#dataTable').DataTable().ajax.reload();
            } else {
              Swal.fire('Error!', response.message, 'error');
            }
          },
          error: function(){
            Swal.fire('Error!', 'Something went wrong while deleting the product.', 'error');
          }
        });
      }
    });
  });

});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laravel Internship projects\ogani-shop\resources\views/admin/products/index.blade.php ENDPATH**/ ?>