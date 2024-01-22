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

    <button type="button" class="btn mb-2" id="filterButton">
        <i class="fa-solid fa-filter"></i> Fillter & Sort
    </button>
    <div class="col-12">
        <form id="searchForm" action="{{ route('admin.product.list') }}" class="mb-4" method="GET" style="display: none;">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Brand:</label>
                                <select class="form-control custom-select select2bs4 @error('brand[]') is-invalid @enderror"
                                    multiple="multiple" data-placeholder="Select categories" style="width: 100%;"
                                    name="brand[]">
                                    <option class="form-control"></option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->name }}"
                                            {{ is_array(old('brand')) && in_array($brand->name, old('brand')) ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Concentration:</label>
                                <select
                                    class="form-control custom-select select2bs4 @error('concentration[]') is-invalid @enderror"
                                    multiple="multiple" data-placeholder="Select categories" style="width: 100%;"
                                    name="concentration[]">
                                    <option class="form-control"></option>
                                    @foreach ($concentrations as $concentration)
                                        <option value="{{ $concentration->name }}"
                                            {{ is_array(old('concentration')) && in_array($concentration->name, old('concentration')) ? 'selected' : '' }}>
                                            {{ $concentration->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Size:</label>
                                <select class="form-control custom-select select2bs4 @error('size[]') is-invalid @enderror"
                                    multiple="multiple" data-placeholder="Select categories" style="width: 100%;"
                                    name="size[]">
                                    <option class="form-control"></option>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->name }}"
                                            {{ is_array(old('size')) && in_array($size->name, old('size')) ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>From:</label>
                                <input type="number" id="inputPrice" class="form-control" step="0.01" name="from"
                                    min="0" value="{{ old('from') }}" placeholder="$">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>To:</label>
                                <input type="number" id="inputPrice" class="form-control" step="0.01" name="to"
                                    min="0" value="{{ old('to') }}" placeholder="$">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Categories</label>
                                <select
                                    class="form-control custom-select select2bs4 @error('category[]') is-invalid @enderror"
                                    multiple="multiple" data-placeholder="Select categories" style="width: 100%;"
                                    name="category[]">
                                    <option class="form-control"></option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}"
                                            {{ is_array(old('category')) && in_array($category->name, old('category')) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group d-flex flex-column justify-content-between">
                                <label>Sale:</label>
                                <input type="checkbox" name="isSale" value="true" data-bootstrap-switch
                                    data-off-color="danger" data-on-color="success">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label>Sort:</label>
                                <select class="select2bs4 select-style-image" name="sortBy">
                                    <option value="0">Default</option>
                                    <option value="1">Last update</option>
                                    <option value="2">Popularity</option>
                                    <option value="3">Reviewest</option>
                                    <option value="4">Price low to high</option>
                                    <option value="5">Price high to low</option>
                                    <option value="6">Average rating</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <input type="search" class="form-control form-control-lg"
                                placeholder="name, description, concentration, brand" name="keySearch">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" value="Save Changes" class="btn btn-success float-right"><i
                            class="fa fa-search"></i> Search</button>
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Products DataTable</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table p-0 table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="text-center">Images</th>
                            <th>Brand</th>
                            <th>Concentration</th>
                            <th>Categories</th>
                            <th>Discounts</th>
                            <th>Quantities</th>
                            <th>Prices($)</th>
                            <th class="text-nowrap">Stars <i class="fas fa-star text-warning text-sm"></i>
                            </th>
                            <th>Reviews / Reviewers</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    {{ $product->id ?? '' }}
                                </td>
                                <td>
                                    <div class="text-truncate-two">{{ $product->name ?? '' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($product->status == 0)
                                        <span class="badge bg-danger">Ceasing</span>
                                    @elseif ($product->status == 1)
                                        <span class="badge bg-success">Opening</span>
                                    @else
                                        <span class="badge bg-warning">Unknown</span>
                                    @endif
                                </td>
                                <td>{{ $product->total }}</td>
                                <td style="text-align: center;">
                                    <img src="{{ displayImageOrDefault($product->images, '/admin-layout/dist/img/photo3.jpg') }}"
                                        alt="{{ $product->name }}" style="display: block; margin: 0 auto;"
                                        width="50px" height="50px">
                                </td>
                                <td>{{ $product->brand->name ?? '' }}</td>
                                <td>{{ $product->concentration->name ?? '' }}</td>
                                <td>
                                    <div class="text-truncate-two">
                                        @foreach ($product->categories as $category)
                                            <span>{{ $category->name ?? '' }}, </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate-two">
                                        @foreach ($product->discounts as $discount)
                                            <span>{{ $discount->name }} - {{ $discount->percent }}%, </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate-two">
                                        @foreach ($product->quantities as $quantity)
                                            <span>{{ $quantity->sizeName }}: {{ $quantity->quantity }},</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>{{ displayProductPriceOrDefault($product->quantities) }}</td>
                                <td>
                                    @if ($product->averageRating > 0)
                                        <span class="text-sm">
                                            {{ $product->averageRating }}
                                            <i class="fas fa-star text-warning"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $product->reviewsCount . '/' . $product->numberOfReviews }}</td>
                                <td class="project-actions text-center py-0 align-middle">
                                    <a class="btn btn-success btn-sm detail-product "
                                        href="{{ route('admin.product.detail', $product->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a id="delete-category" class="btn btn-danger btn-sm"
                                        href="{{ route('admin.product.delete', $product->id) }}">
                                        <i class="fas fa-trash">
                                        </i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <script>
        $(document).on('click', '#delete-category', function(e) {
            e.preventDefault();
            let deleteUrl = $(this).attr('href');
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this imaginary file!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location.href = deleteUrl;
                }
            });

        })
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "ordering": false,
                "autoWidth": false,
                "buttons": ["csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    <script>
        const resetButton = document.querySelector("#searchForm button[type='reset']");

        resetButton.addEventListener("click", function() {
            document.querySelectorAll("#searchForm input, #searchForm select").forEach(function(element) {
                if (element.type === "select") {
                    element.selectedIndex = 0;
                } else {
                    element.value = "";
                }
            });
        });

        $(document).ready(function() {
            $('#filterButton').on('click', function() {
                $('#searchForm').slideToggle();
            });
        });
    </script>
@endsection
