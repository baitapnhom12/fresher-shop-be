@extends('layouts.app')
@section('content')
    @if (session('success'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.success("{{ session('success') }}", 'Success!', {
                timeOut: 3000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            toastr.options = {
                "progressBar": true,
                "closeButton": true,
            }
            toastr.error("{{ session('error') }}", 'Error!', {
                timeOut: 3000
            });
        </script>
    @endif

    <div class="col-12">
        <div class="d-flex justify-content-end mb-2">
            <div class=" clearfix mr-2">
                <button type="button" class="btn btn-primary " id="addSizeBtn"><i class="fas fa-plus"></i> Add
                    size</button>
            </div>

            <div class=" clearfix">
                <button type="button" class="btn btn-secondary " id="addDiscountBtn"><i class="fas fa-plus"></i> Add
                    discount</button>
            </div>
        </div>

        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="inputName">Product Name</label>
                                <input type="text" id="inputName" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ $product->name }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="inputName">Product Slug</label>
                                <input type="text" id="inputSlug" class="form-control" value="{{ $product->slug }}"
                                    readonly>
                            </div>

                            <div class="form-group">
                                <label for="inputBrand">Brand</label>
                                <select id="inputBrand" style="width: 100%;"
                                    class="form-control custom-select select2bs4 @error('brandId') is-invalid @enderror"
                                    name="brandId">
                                    <option value="{{ $product->brand->id }}" selected>{{ $product->brand->name }}
                                    </option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}" checked>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inputConcentration">Concentration</label>
                                <select id="inputConcentration" name="concentrationId"
                                    class="form-control custom-select select2bs4 @error('concentrationId') is-invalid @enderror">
                                    <option value="{{ $product->concentration->id }}" selected>
                                        {{ $product->concentration->name }}</option>
                                    @foreach ($concentrations as $concentration)
                                        <option value="{{ $concentration->id }}" checked>{{ $concentration->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('concentrationId')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Features</label>
                                <select
                                    class="form-control custom-select select2bs4 @error('featureIds[]') is-invalid @enderror"
                                    multiple="multiple" data-placeholder="Select a Category" style="width: 100%;"
                                    name="featureIds[]">
                                    @foreach ($product->features as $feature)
                                        <option value="{{ $feature->id }}" selected>
                                            {{ $feature->feature }}</option>
                                    @endforeach
                                    @foreach ($features as $item)
                                        <option value="{{ $item->id }}" checked>{{ $item->feature }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inputCategory">Categories</label>
                                <select id="inputCategory"
                                    class="form-control custom-select select2bs4 @error('categoryIds[]') is-invalid @enderror"
                                    multiple="multiple" data-placeholder="Select a Category" style="width: 100%;"
                                    name="categoryIds[]">
                                    @foreach ($product->categories as $category)
                                        <option value="{{ $category->id }}" selected>
                                            {{ $category->name }}</option>
                                    @endforeach
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" checked>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Open for sales</label>
                                <div class="d-flex flex-wrap" style="gap: 0 15px">
                                    <div class="checkbox-wrapper-14">
                                        <input id="s1-14" type="checkbox" class="switch" value="1" name="status"
                                            onclick="onlyPopular(this)"
                                            @if ($product->status) @checked(true) @endif>
                                        <span>Open</span>
                                    </div>
                                    <div class="checkbox-wrapper-14">
                                        <input id="s1-14" type="checkbox" class="switch" value="0" name="status"
                                            onclick="onlyPopular(this)"
                                            @if (!$product->status) @checked(true) @endif>
                                        <span>Cease</span>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="inputName">Total Sold</label>
                                <input type="text" class="form-control" value="{{ $product->quantitySold }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="inputDescription">Product Description</label>
                                <textarea id="summernote" name="description" required class="form-control @error('description') is-invalid @enderror"
                                    rows="4">{{ $product->description }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>IsMain</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->images as $img)
                                            <tr>
                                                <td><img src="{{ $img->path }}" alt="{{ $product->name }}"
                                                        width="50px" height="50px"></td>
                                                <td><input class="form-check-input ml-2" type="radio" name="radio1"
                                                        @if ($img->main) @checked(true) @endif
                                                        data-image-id="{{ $img->id }}">
                                                </td>
                                                <td>
                                                    <a class="btn btn-danger btn-sm image-delete"
                                                        data-image-id="{{ $img->id }}" href="#">
                                                        <i class="fas fa-trash">
                                                        </i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                                <label for="images">Add Images</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="images[]" id="images" class="custom-file-input"
                                            placeholder="Choose images" multiple>
                                        <label class="custom-file-label" for="images">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            @error('images')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                            @enderror
                            <div class="col-md-12">
                                <div class="mt-1 text-center">
                                    <div class="images-preview-div d-flex align-items-center"> </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="imageDelete">
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="imageUpdate">
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="sizeDelete" class="size-delete-input">
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="discountDelete" class="discount-delete-input">
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>

                    <!-- /.card -->
                </div>

                <div class="col-sm-4" id="productInfo">
                    @if (!count($product->quantities))
                        <div class="size-product">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Size</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool remove-size-discount-card"
                                            data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="inputSize">Size</label>
                                        <select id="inputSize" style="width: 100%;"
                                            class="form-control select2bs4 @error('sizeId') is-invalid @enderror"
                                            name="sizeId[]">
                                            <option class="form-control"></option>
                                            @foreach ($sizes as $size)
                                                <option value="{{ $size->id }}"
                                                    {{ is_array(old('sizeId')) && in_array($size->id, old('sizeId')) ? 'selected' : '' }}>
                                                    {{ $size->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('sizeId')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputQuantity">Quantity</label>
                                        <input type="number" id="inputQuantity"
                                            class="form-control @error('quantity') is-invalid @enderror" step="1"
                                            name="quantity[]" min="0"
                                            value="{{ is_array(old('quantity')) ? old('quantity')[0] : old('quantity') }}">
                                        @error('quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPrice">Price</label>
                                        <input type="number" id="inputPrice"
                                            class="form-control @error('price') is-invalid @enderror" step="0.01"
                                            name="price[]" min="0"
                                            value="{{ is_array(old('price')) ? old('price')[0] : old('price') }}">
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    @endif

                    @if (!count($product->discounts))
                        <div class="discout-product">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Discount</h3>

                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool remove-size-discount-card"
                                            data-card-widget="remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="inputDiscount">Discount</label>
                                        <select style="width: 100%;"
                                            class="form-control select2bs4 @error('discountId') is-invalid @enderror"
                                            name="discountId[]">
                                            <option class="form-control"></option>
                                            @foreach ($discounts as $discount)
                                                <option value="{{ $discount->id }}"
                                                    {{ is_array(old('discountId')) && in_array($discount->id, old('discountId')) ? 'selected' : '' }}>
                                                    {{ $discount->name }} /
                                                    {{ $discount->percent }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('discountId')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="inputUsage">Usage Count</label>
                                        <input type="number" id="inputUsage"
                                            class="form-control @error('usageCount') is-invalid @enderror" step="1"
                                            name="usageCount[]" min="0"
                                            value="{{ is_array(old('usageCount')) ? old('usageCount')[0] : old('usageCount') }}">
                                        @error('usageCount')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Promotion Term</label>
                                        <div class="input-group date" id="reservationdatetime"
                                            data-target-input="nearest">
                                            <input type="text"
                                                class="form-control datetimepicker-input @error('promotionTerm') is-invalid @enderror"
                                                data-target="#reservationdatetime" id="inputPromotionTerm"
                                                name="promotionTerm[]"
                                                value="{{ is_array(old('promotionTerm')) ? old('promotionTerm')[0] : old('promotionTerm') }}" />
                                            <div class="input-group-append" data-target="#reservationdatetime"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('promotionTerm')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>

                        </div>
                    @endif

                    @foreach ($product->quantities as $quantity)
                        <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">{{ $product->name }} / {{ $quantity->sizeName }}</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool remove-size-btn remove-size-discount-card"
                                        data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="inputSize">Size</label>
                                    <select id="inputSize" name="sizeId[]" style="width: 100%;"
                                        class="form-control  @error('sizeId') is-invalid @enderror" @readonly(true)>
                                        <option value="{{ $quantity->sizeId }}" checked>
                                            {{ $quantity->sizeName }}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputQuantity">Quantity</label>
                                    <input type="number" id="inputQuantity"
                                        class="form-control @error('quantity') is-invalid @enderror"
                                        value="{{ $quantity->quantity }}" step="1" name="quantity[]"
                                        min="0">
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="inputPrice">Price</label>
                                    <input type="number" id="inputPrice"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ $quantity->price }}" step="0.01" name="price[]" min="0">
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    @endforeach

                    @foreach ($product->discounts as $discountCurrent)
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $product->name }} / {{ $discountCurrent->name }} /
                                    {{ $discountCurrent->percent }}</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-tool remove-discount-btn remove-size-discount-card"
                                        data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="inputDiscount">Discount</label>
                                    <select id="inputDiscount" style="width: 100%;"
                                        class="form-control  @error('discountId') is-invalid @enderror"
                                        name="discountId[]" @readonly(true)>
                                        <option value="{{ $discountCurrent->discountId }}" checked>
                                            {{ $discountCurrent->name }}
                                            {{ $discountCurrent->percent }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputUsage">Usage count</label>
                                    <input type="number" id="inputUsage"
                                        class="form-control @error('usageCount') is-invalid @enderror" step="1"
                                        name="usageCount[]" min="0" value="{{ $discountCurrent->usageCount }}">
                                </div>

                                <div class="form-group">
                                    <label>Promotion Term</label>
                                    <div class="input-group date">
                                        <input type="datetime-local"
                                            class="form-control @error('promotionTerm') is-invalid @enderror"
                                            name="promotionTerm[]" value="{{ $discountCurrent->promotionTerm }}"
                                            step="1">
                                    </div>
                                    @error('promotionTerm')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    @endforeach
                    <!-- /.card -->
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <a href="#" class="btn btn-secondary">Cancel</a>
                    <input type="submit" value="Save Changes" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </div>

    <script>
        let counter = 1;
        document.getElementById('addDiscountBtn').addEventListener('click', function() {
            let newID = 'reservationdatetime' + counter;
            let productDiscountDiv = document.getElementById('productInfo');
            // Tạo một đối tượng div mới
            let newDiscount = document.createElement('div');
            newDiscount.classList.add('discount-product');

            // Thêm nội dung HTML vào đối tượng div mới
            newDiscount.innerHTML = `
                <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Discount</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool remove-discount-card" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="inputDiscount">Discount</label>
                                    <select style="width: 100%;" class="form-control custom-select select2bs4 @error('discountId') is-invalid @enderror" name="discountId[]">
                                        <option class="form-control"></option>
                                        @foreach ($discounts as $discount)
                                            <option value="{{ $discount->id }}" checked>{{ $discount->name }} /
                                                {{ $discount->percent }}</option>
                                        @endforeach
                                    </select>
                                    @error('discountId')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="inputUsage">Usage Count</label>
                                    <input type="number" id="inputUsage" class="form-control @error('usageCount') is-invalid @enderror" step="1" name="usageCount[]" min="0" required>
                                    @error('usageCount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Promotion Term</label>
                                    <div class="input-group date" id="${newID}" data-target-input="nearest">
                                        <input type="text"
                                            class="form-control datetimepicker-input @error('promotionTerm') is-invalid @enderror"
                                            data-target="#${newID}" 
                                            name="promotionTerm[]" required/>
                                        <div class="input-group-append" data-target="#${newID}"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                    @error('promotionTerm')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
            `;
            productDiscountDiv.appendChild(newDiscount);
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
            $("#" + newID).datetimepicker({
                icons: {
                    time: 'far fa-clock'
                },
                format: 'MM/DD/YYYY HH:mm:ss'
            });

            counter++;

            let removeButtons = document.querySelectorAll('.remove-discount-card');

            removeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var card = this.closest('.card');
                    if (card) {
                        card.remove(); // Xóa phần tử cha (thẻ card) khi nút được click
                    }
                });
            });
        })

        document.getElementById('addSizeBtn').addEventListener('click', function() {
            let productInfoDiv = document.getElementById('productInfo');

            let newCardDiv = document.createElement('size-product');

            newCardDiv.innerHTML = `
                <div class="card ">
                            <div class="card-header">
                                <h3 class="card-title">Size</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                        title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool remove-size-card" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label >Size</label>
                                    <select style="width: 100%;"
                                        class="form-control  select2bs4 @error('sizeId') is-invalid @enderror"
                                        name="sizeId[]">
                                        <option class="form-control"></option>
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}" checked>{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sizeId')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="inputQuantity">Quantity</label>
                                    <input type="number" id="inputQuantity" class="form-control @error('quantity') is-invalid @enderror" step="1" name="quantity[]"min="0" required>
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="inputPrice">Price</label>
                                    <input type="number" id="inputPrice" class="form-control @error('price') is-invalid @enderror" step="0.01" name="price[]" min="0" required>
                                    @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
            `;
            productInfoDiv.appendChild(newCardDiv);
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            let removeButtons = document.querySelectorAll('.remove-size-card');

            removeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var card = this.closest('.card');
                    if (card) {
                        card.remove(); // Xóa phần tử cha (thẻ card) khi nút được click
                    }
                });
            });
        });
    </script>

    <script>
        function onlyPopular(checkbox) {
            var checkboxes = document.getElementsByName('status')
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false
            })
        }
        $(function() {
            // Multiple images preview with JavaScript
            var previewImages = function(input, imgPreviewPlaceholder) {

                if (input.files) {
                    var filesAmount = input.files.length;

                    for (i = 0; i < filesAmount; i++) {
                        var reader = new FileReader();

                        reader.onload = function(event) {
                            $($.parseHTML('<img class="pr-3">')).attr('src', event.target.result).attr(
                                'style',
                                'max-width: 150px; height: auto;').appendTo(
                                imgPreviewPlaceholder).on('click', function() {
                                $(this).remove();
                            });
                        }

                        reader.readAsDataURL(input.files[i]);
                    }
                }

            };

            $('#images').on('change', function() {
                $('.images-preview-div').empty(); // Clear preview images before adding new ones
                previewImages(this, 'div.images-preview-div');
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.image-delete');
            const radioButtons = document.querySelectorAll('input[name="radio1"]');

            let selectedImages = [];

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    const imageId = this.getAttribute('data-image-id');
                    const imageRow = this.closest('tr').querySelector('img');

                    if (imageRow) {
                        if (!selectedImages.includes(imageId)) {
                            imageRow.style.opacity = '0.2';
                            selectedImages.push(imageId);
                        } else {
                            imageRow.style.opacity = '1';
                            const index = selectedImages.indexOf(imageId);
                            if (index > -1) {
                                selectedImages.splice(index, 1);
                            }
                        }

                        // Update value của input[name="imageDelete"] thành mảng các ID đã chọn
                        const deleteInput = document.querySelector('input[name="imageDelete"]');
                        if (deleteInput) {
                            deleteInput.value = JSON.stringify(selectedImages);
                        }
                    }
                });
            });

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    const imageId = this.getAttribute('data-image-id');
                    // Thêm vào input[type=text] với tên là imageUpdate
                    const updateInput = document.querySelector('input[name="imageUpdate"]');
                    if (updateInput) {
                        updateInput.value = imageId;
                    }
                });
            });

            const removeButtons = document.querySelectorAll('.remove-size-btn');
            const sizeDeleteInput = document.querySelector('.size-delete-input');

            removeButtons.forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    const sizeId = this.closest('.card').querySelector('select[name="sizeId[]"]')
                        .value;

                    // Lấy giá trị hiện tại của sizeDeleteInput và chuyển thành mảng (nếu có)
                    const currentSizeDelete = sizeDeleteInput.value ? JSON.parse(sizeDeleteInput
                        .value) : [];

                    // Thêm sizeId vào mảng nếu chưa tồn tại
                    if (!currentSizeDelete.includes(sizeId)) {
                        currentSizeDelete.push(Number(sizeId));

                        // Cập nhật giá trị của sizeDeleteInput
                        sizeDeleteInput.value = JSON.stringify(currentSizeDelete);
                    }
                });
            });

            const removeDiscountButtons = document.querySelectorAll('.remove-discount-btn');
            const discountDeleteInput = document.querySelector('.discount-delete-input');

            removeDiscountButtons.forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    const discountId = this.closest('.card').querySelector(
                            'select[name="discountId[]"]')
                        .value;

                    // Lấy giá trị hiện tại của discountDeleteInput và chuyển thành mảng (nếu có)
                    const currentSizeDelete = discountDeleteInput.value ? JSON.parse(
                        discountDeleteInput
                        .value) : [];

                    // Thêm discountId vào mảng nếu chưa tồn tại
                    if (!currentSizeDelete.includes(discountId)) {
                        currentSizeDelete.push(Number(discountId));

                        // Cập nhật giá trị của discountDeleteInput
                        discountDeleteInput.value = JSON.stringify(currentSizeDelete);
                    }
                });
            });

            var removeSizeCard = document.querySelectorAll('.remove-size-discount-card');

            removeSizeCard.forEach(function(button) {
                button.addEventListener('click', function() {
                    var card = this.closest('.card');
                    if (card) {
                        card.remove();
                    }
                });
            });
        });
    </script>
@endsection
