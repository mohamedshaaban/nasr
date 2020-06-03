@extends('vendors.layouts.app')
@section('title' , 'Product Edit')
@section('custom_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all" rel="stylesheet"
          type="text/css" />
    <style type="text/css">
        .main-section {
            margin: 0 auto;
            padding: 20px;
            margin-top: 100px;
            background-color: #fff;
            box-shadow: 0px 0px 20px #c1c1c1;
        }

        .fileinput-remove,
        .fileinput-upload {
            display: none;
        }
    </style>
@endsection
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Product Managment
                {{--  <small>advanced tables</small>  --}}
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url('vendor/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{ url('vendor/products')}}">Products Managment</a></li>
                <li class="active">Create</li>
            </ol>
        </section>
        @if(Session::has('success'))
            <div class="pad margin no-print">
                <div class="callout callout-success" style="margin-bottom: 0!important;">
                    <h4><i class="fa fa-success"></i> Success:</h4>
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif
        <section class="content">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Product</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-minus"></i>
                        </button> {{-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>                    --}}
                    </div>
                </div>
                <div class="box-body">
                    <form class="form-horizontal" method="post" enctype="multipart/form-data"
                          action="{{ route('vendor.product.store' , [$product->id])}}">

                        <input type="hidden" name="id" value="{{ $product->id }}"/>
                        @csrf
                        <div class="box-body">

                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">

                                    <li class="active">
                                        <a href="#tab-form-1" data-toggle="tab">
                                            Product Informations <i class="fa fa-exclamation-circle text-red hide"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab-form-2" data-toggle="tab">
                                            images <i class="fa fa-exclamation-circle text-red hide"></i>
                                        </a>
                                    </li>

                                    <li  >
                                        <a href="#tab-form-3" data-toggle="tab">
                                            Sizes <i class="fa fa-exclamation-circle text-red hide"></i>
                                        </a>
                                    </li>

                                    <li >
                                        <a href="#tab-form-4" data-toggle="tab">
                                            Life <i class="fa fa-exclamation-circle text-red hide"></i>
                                        </a>
                                    </li>


                                </ul>
                                <div class="tab-content fields-group">

                                    <div class="tab-pane active" id="tab-form-1">

                                        <div class="form-group  ">

                                            <label for="name_en" class="col-sm-2 asterisk control-label">Name
                                                English</label>

                                            <div class="col-sm-8">


                                                <div class="input-group">

                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>

                                                    <input required type="text" id="name_en" name="name_en"
                                                           value="{{ $product->name_en }}" class="form-control name_en"
                                                           placeholder="Input Name English">


                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group  ">

                                            <label for="name_ar" class="col-sm-2 asterisk control-label">Name
                                                Arabic</label>

                                            <div class="col-sm-8">


                                                <div class="input-group">

                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>

                                                    <input required type="text" id="name_ar" name="name_ar"
                                                           value="{{ $product->name_ar }}" class="form-control name_ar"
                                                           placeholder="Input Name Arabic">


                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group hidden ">

                                            <label for="slug_name" class="col-sm-2 asterisk control-label">Slug
                                                name</label>

                                            <div class="col-sm-8">


                                                <div class="input-group">

                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>

                                                    <input required type="text" id="slug_name" name="slug_name"
                                                           value="{{ $product->slug_name }}"
                                                           class="form-control slug_name"
                                                           disabled
                                                           placeholder="Input Slug name">


                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group hidden ">

                                            <label for="sku" class="col-sm-2 asterisk control-label">Sku</label>

                                            <div class="col-sm-8">


                                                <div class="input-group">

                                                    <span class="input-group-addon"><i
                                                            class="fa fa-pencil fa-fw"></i></span>

                                                    <input required type="text" id="sku" name="sku"
                                                           value="{{ $product->sku ? $product->sku : (substr(md5(mt_rand()), 0, 7)) }}" class="form-control sku"
                                                           placeholder="Input Sku">


                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group  ">

                                            <label for="categories" class="col-sm-2  control-label">Categories</label>

                                            <div class="col-sm-8">


                                                <select class="form-control categories select2-hidden-accessible"
                                                        style="width: 100%;" name="categories[]" id="categories" multiple=""
                                                        onchange="getSubCategories(this.value)"
                                                        data-placeholder="Input Categories" data-value="2" tabindex="-1"
                                                        aria-hidden="true">
                                                    @if(isset($categories))
                                                    @foreach($categories as $category)

                                                        <option
                                                            @if(isset($product->categories)) @if(in_array($category->id, $product->categories->pluck('id')->toArray())) selected @endif @endif
                                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                        @endif
                                                </select>



                                            </div>
                                        </div>
                                        <div class="form-group  ">

                                            <label for="categories" class="col-sm-2  control-label">Sub Categories</label>

                                            <div class="col-sm-8">


                                                <select class="form-control categories select2-hidden-accessible"
                                                        style="width: 100%;" name="categories[]" multiple="" id="subCategories"

                                                        data-placeholder="Input Categories" data-value="2" tabindex="-1"
                                                        aria-hidden="true">
                                                    @foreach($subcategories as $category)

                                                        <option
                                                            @if(isset($product->categories)) @if(in_array($category->id, $product->categories->pluck('id')->toArray())) selected @endif @endif
                                                            value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endforeach
                                                </select>



                                            </div>
                                        </div>

                                        <input type="hidden" name="vendor_id" value="{{ $vendor_id }}">


                                        <div class="form-group  ">

                                            <label for="product_type"
                                                   class="col-sm-2 asterisk control-label">Type</label>

                                            <div class="col-sm-8">



                                                <select class="form-control product_type select2-hidden-accessible"
                                                        style="width: 100%;" name="product_type" required
                                                        data-value="0" tabindex="-1" aria-hidden="true">
                                                    <option value=""></option>
                                                    <option value="0" @if($product->product_type == 0) selected @endif>Normal</option>
                                                    <option value="1" @if($product->product_type == 1) selected @endif>Box</option>
                                                    <option value="3" @if($product->product_type == 3) selected @endif>Felline</option>
                                                    <option value="4" @if($product->product_type == 4) selected @endif>Carton</option>
                                                    <option value="5" @if($product->product_type == 5) selected @endif>Sack</option>
                                                    <option value="2" @if($product->product_type == 2) selected @endif>Live</option>
                                                    <option value="6" @if($product->product_type == 6) selected @endif>Bale</option>
                                                    <option value="7" @if($product->product_type == 7) selected @endif>Band</option>
                                                </select>


                                            </div>
                                        </div>


                                        <div class="form-group  ">

                                            <label for="short_description_en" class="col-sm-2 asterisk control-label">Short
                                                description en</label>

                                            <div class="col-sm-8">


                                                <textarea name="short_description_en"
                                                          class="form-control short_description_en" rows="5"
                                                          placeholder="Input Short description en"
                                                          required>{{ $product->short_description_en }}</textarea>


                                            </div>
                                        </div>

                                        <div class="form-group  ">

                                            <label for="short_description_ar" class="col-sm-2 asterisk control-label">Short
                                                description ar</label>

                                            <div class="col-sm-8">


                                                <textarea name="short_description_ar"
                                                          class="form-control short_description_ar" rows="5"
                                                          placeholder="Input Short description ar" required>{{ $product->short_description_ar }}
                                                </textarea>


                                            </div>
                                        </div>

                                        <div class="form-group  ">

                                            <label for="description_en" class="col-sm-2 asterisk control-label">Description
                                                en</label>

                                            <div class="col-sm-8">


                                                <textarea name="description_en" class="form-control description_en"
                                                          rows="5" placeholder="Input Description en" required>{{ $product->description_en }}
                                                </textarea>


                                            </div>
                                        </div>

                                        <div class="form-group  ">

                                            <label for="description_ar" class="col-sm-2 asterisk control-label">Description
                                                ar</label>

                                            <div class="col-sm-8">


                                                <textarea name="description_ar" class="form-control description_ar"
                                                          rows="5" placeholder="Input Description ar" required>{{ $product->description_ar }}
                                                </textarea>


                                            </div>
                                        </div>

                                        <div class="form-group  ">

                                            <label for="price" class="col-sm-2 asterisk control-label">Price</label>

                                            <div class="col-sm-8">


                                                <div class="input-group">


                                                    <div class="input-group"><input
                                                            required style="width: 100px; text-align: center;"
                                                            type="text" id="price" name="price"
                                                            step="0.01"
                                                            value="{{ $product->price }}"
                                                            class="form-control price initialized"
                                                            placeholder="Input Price">
                                                    </div>


                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group  ">

                                            <label for="quantity"
                                                   class="col-sm-2 asterisk control-label">Quantity</label>

                                            <div class="col-sm-8">


                                                <div class="input-group">


                                                    <div class="input-group"><input
                                                            required style="width: 100px; text-align: center;"
                                                            type="number" id="quantity" name="quantity"
                                                            value="{{ $product->quantity }}"
                                                            class="form-control quantity initialized"
                                                            placeholder="Input Quantity">
                                                    </div>


                                                </div>


                                            </div>
                                        </div>
                                        <div class="form-group  ">

                                            <label for="status" class="col-sm-2  control-label">active</label>

                                            <div class="col-sm-8">


                                                <label class="radio-inline">
                                                    <div style="position: relative;"
                                                         aria-checked="false" aria-disabled="false"><input type="radio"
                                                                                                           name="status"
                                                                                                           {{ $product->status == 0 ? 'checked' : '' }}
                                                                                                           value="0"
                                                                                                           class="minimal status"
                                                                                                           style="position: absolute; opacity: 0;">

                                                    </div>&nbsp;no&nbsp;&nbsp;
                                                </label>
                                                <label class="radio-inline">
                                                    <div style="position: relative;"
                                                         aria-checked="false" aria-disabled="false"><input type="radio"
                                                                                                           name="status"
                                                                                                           value="1"
                                                                                                           class="minimal status"
                                                                                                           {{ $product->status == 1 ? 'checked' : '' }}


                                                                                                           style="position: absolute; opacity: 0;">

                                                    </div>&nbsp;yes&nbsp;&nbsp;
                                                </label>


                                            </div>
                                        </div>


                                    </div>
                                    <div class="tab-pane " id="tab-form-2">
                                        <div class="form-group  ">

                                            <label for="main_image" class="col-sm-2  control-label">Main Image </label>

                                            <div class="col-sm-8">


                                                <div class="file-input">


                                                    <input type="file"
                                                           class="main_image"
                                                           name="main_image"
                                                           id="main_image"
                                                    >


                                                </div>


                                            </div>
                                        </div>

                                        <div class="form-group  ">

                                            <label for="images" class="col-sm-2  control-label">Images</label>

                                            <div class="col-sm-8">

                                                <div class="file-input">


                                                    <input type="file"
                                                           class="main_image"
                                                           name="images[]"
                                                           id="images"
                                                           multiple
                                                    >

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane " id="tab-form-3">

                                        <div class="row">
                                            <div class="col-sm-2 "><h4 class="pull-right">Sizes</h4></div>
                                            <div class="col-sm-8"></div>
                                        </div>



                                        <div id="has-many-productsizes" class="has-many-productsizes">

                                            <div class="has-many-productsizes-forms">
                                                @if(isset($product->productsizes))
                                                @foreach($product->productsizes as $key => $productsize)

                                                    <div class="has-many-productsizes-form fields-group"
                                                         id="productSize_{{ $productsize->id }}">

                                                        <div class="form-group  ">

                                                            <label for="description" class="col-sm-2  control-label">Description</label>

                                                            <div class="col-sm-8">


                                                                <div class="input-group">

                                                                <span class="input-group-addon"><i
                                                                        class="fa fa-pencil fa-fw"></i></span>

                                                                    <input type="text" id="description"
                                                                           name="productsizes[{{ $key }}][description]"
                                                                           value="{{ $productsize->description }}"
                                                                           class="form-control productsizes description"
                                                                           placeholder="Input Description">


                                                                </div>


                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">

                                                            <label for="price" class="col-sm-2  control-label">Price</label>

                                                            <div class="col-sm-8">


                                                                <div class="input-group">

                                                                <span class="input-group-addon"><i
                                                                        class="fa fa-pencil fa-fw"></i></span>

                                                                    <input type="text" id="description"
                                                                           name="productsizes[{{ $key }}][price]"
                                                                           value="{{ $productsize->price }}"
                                                                           class="form-control productsizes price"
                                                                           placeholder="Input Price">


                                                                </div>


                                                            </div>
                                                        </div>

                                                        <div class="form-group  ">

                                                            <label for="sizes_id"
                                                                   class="col-sm-2  control-label">Sizes</label>

                                                            <div class="col-sm-8">


                                                                <select
                                                                    class="form-control productsizes selectproductsizes sizes_id select2-hidden-accessible"
                                                                    style="width: 100%;"
                                                                    name="productsizes[{{ $key }}][value]"
                                                                    data-value="1" tabindex="-1" aria-hidden="true">
                                                                    @foreach($sizes as $size)

                                                                        <option
                                                                            value="{{ $size->id }}"
                                                                            @if($size->id == $productsize->sizes_id) selected @endif>{{ $size->name }}</option>
                                                                    @endforeach
                                                                </select>


                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="col-sm-2  control-label"></label>
                                                            <div class="col-sm-8">
                                                                <div class="remove btn btn-warning btn-sm pull-right"
                                                                     onclick="removeSize({{ $productsize->id }})"><i
                                                                        class="fa fa-trash">&nbsp;</i>Remove
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                @endforeach
                                                @endif
                                                <div id="addproductsizes"></div>

                                            </div>
                                            <input type="hidden" id="sizecount" value="{{ isset($product->productsizes) ?  count($product->productsizes) : 0 }}" />

                                            <div class="form-group">
                                                <label class="col-sm-2  control-label"></label>
                                                <div class="col-sm-8">
                                                    <div class="add btn btn-success btn-sm" onclick="addNewSize({{ isset($product->productsizes) ?  count($product->productsizes) : 0 }})"><i
                                                            class="fa fa-save"></i>&nbsp;New
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane " id="tab-form-4">
                                        <div class="form-group  ">

                                            <label for="breeds" class="col-sm-2  control-label">Breeds</label>

                                            <div class="col-sm-8">


                                                <select class="form-control breeds select2-hidden-accessible"
                                                        style="width: 100%;" name="breeds[]" multiple=""
                                                        data-placeholder="Input Breeds" data-value="1,2" tabindex="-1"
                                                        aria-hidden="true">
                                                    @foreach($breeds as $breed)
                                                        <option value="{{ $breed->id }}"
                                                               @if(isset($product->breeds)) @if(in_array($breed->id, $product->breeds->pluck('id')->toArray())) selected @endif @endif>{{ $breed->name }}</option>
                                                    @endforeach
                                                </select>


                                            </div>
                                        </div>

                                        <div class="form-group  ">

                                            <label for="genders" class="col-sm-2  control-label">Genders</label>

                                            <div class="col-sm-8">


                                                <select class="form-control genders select2-hidden-accessible"
                                                        style="width: 100%;" name="genders[]" multiple=""
                                                        data-placeholder="Input Genders" data-value="2" tabindex="-1"
                                                        aria-hidden="true">
                                                    @foreach($genders as $gender)
                                                        <option value="{{ $gender->id }}"
                                                                @if(isset($product->genders)) @if(in_array($gender->id, $product->genders->pluck('id')->toArray())) selected @endif @endif>{{ $gender->name }}</option>
                                                    @endforeach
                                                </select>


                                            </div>
                                        </div>

                                        <div class="form-group  ">

                                            <label for="ages" class="col-sm-2  control-label">Ages</label>

                                            <div class="col-sm-8">


                                                <select class="form-control ages select2-hidden-accessible"
                                                        style="width: 100%;" name="ages[]" multiple=""
                                                        data-placeholder="Input Ages" data-value="1" tabindex="-1"
                                                        aria-hidden="true">
                                                    @foreach($ages as $age)
                                                        <option value="{{ $age->id }}"
                                                                @if(isset($product->ages))  @if(in_array($age->id, $product->ages->pluck('id')->toArray())) selected @endif @endif>{{ $age->name }}</option>
                                                    @endforeach
                                                </select>


                                            </div>
                                        </div>

                                        <div class="form-group  ">

                                            <label for="colors" class="col-sm-2  control-label">Colors</label>

                                            <div class="col-sm-8">


                                                <select class="form-control colors select2-hidden-accessible"
                                                        style="width: 100%;" name="colors[]" multiple=""
                                                        data-placeholder="Input Colors" data-value="1" tabindex="-1"
                                                        aria-hidden="true">
                                                    @foreach($colors as $color)
                                                        <option value="{{ $color->id }}"
                                                                @if(isset($product->colors)) @if(in_array($color->id, $product->colors->pluck('id')->toArray())) selected @endif @endif>{{ $color->name }}</option>
                                                    @endforeach
                                                </select>


                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>


    </div>



@endsection

@section('lower_javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js"
            type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js"
            type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
            type="text/javascript"></script>

    <script type="text/javascript">
        $("#images").fileinput({

            allowedFileExtensions: ['jpg', 'png', 'gif'],
            overwriteInitial: false,
            maxFileSize: 2000,
            maxFilesNum: 10,
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            },
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
            initialPreview: [
                @if($product->images)
                @foreach ($product->images as $image )
                    '{{ asset('/uploads/'.$image) }}',
                @endforeach
                @endif
            ]
        });



    </script>
    @if($product->main_image != "")
    <script>
        $("#main_image").fileinput({

            allowedFileExtensions: ['jpg', 'png', 'gif'],
            overwriteInitial: false,
            maxFileSize: 2000,
            maxFilesNum: 10,
            slugCallback: function (filename) {
                return filename.replace('(', '_').replace(']', '_');
            },
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
            initialPreview: [
                '{{ ($product->main_image != "") ? asset('/uploads/'.$product->main_image) : '' }}',
            ]
        });
    </script>
        @else
        <script>
            $("#main_image").fileinput({

                allowedFileExtensions: ['jpg', 'png', 'gif'],
                overwriteInitial: false,
                maxFileSize: 2000,
                maxFilesNum: 10,
                slugCallback: function (filename) {
                    return filename.replace('(', '_').replace(']', '_');
                },
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',

            });
        </script>
    @endif
    <script>

        function removeSize(sizeid) {
            var element = document.getElementById('productSize_' + sizeid);
            element.parentNode.removeChild(element);
        }

        function addNewSize(index) {
            var current_id = parseInt($('#sizecount').val())+1;
            var data = $('[name="product_type"]').val();
            var options='';
            $.ajax({
                method: "get",
                url: "/vendor/getsizestype/"+data,

                success: function(result) {
                    $(".selectproductsizes").empty();
                    $.each(result, function(i, item) {
                        $(".selectproductsizes").append(
                            $("<option>")
                                .attr("value", item.id)
                                .text(item.name_en)
                        );
                    });

                    $.each(result, function(i, item) {
                        options+='<option value="'+item.id+'">'+item.name_en+'</option>';

                    });
                }
            });
            console.log(options);
            var newElement = ' <div class="add_class_add">\n' +
                '                                                        <div class="form-group  ">\n' +
                '                                                            <label for="description" class="col-sm-2  control-label">Description</label>\n' +
                '                                                            <div class="col-sm-8">\n' +
                '                                                                <div class="input-group">\n' +
                '                                                                    <span class="input-group-addon"><i\n' +
                '                                                                            class="fa fa-pencil fa-fw"></i></span>\n' +
                '                                                                    <input type="text" id="description"\n' +
                '                                                                           name="productsizes['+current_id+'][description]" value=""\n' +
                '                                                                           class="form-control productsizes description"\n' +
                '                                                                           placeholder="Input Description">\n' +
                '                                                                </div>\n' +
                '                                                            </div>\n' +
                '                                                        </div>\n' +
                '<div class="form-group  ">\n' +
                '<label for="price" class="col-sm-2  control-label">Price</label>\n' +
                '<div class="col-sm-8">\n' +
                '<div class="input-group">\n' +
         '       <span class="input-group-addon"><i\n' +
        'class="fa fa-pencil fa-fw"></i></span>\n' +
          '  <input type="text" id="description"\n' +
         '   name="productsizes['+current_id+'][price]"\n' +
        '    value=""\n' +
        'class="form-control productsizes price"\n' +
            'placeholder="Input Price">\n' +
                '</div>\n' +
                '</div>\n' +
                '</div>\n' +
                '                                                        <div class="form-group  ">\n' +
                '\n' +
                '                                                            <label for="sizes_id"\n' +
                '                                                                   class="col-sm-2  control-label">Sizes</label>\n' +
                '\n' +
                '                                                            <div class="col-sm-8">\n' +
                '                                                                <select class="form-control productsizes selectproductsizes sizes_id"\n' +
                '                                                                        style="width: 100%;"\n' +
                '                                                                        name="productsizes['+current_id+'][value]" data-value="">\n' +
                '                                                                   '+options+'\n' +
                '                                                                </select>\n' +
                '                                                            </div>\n' +
                '                                                        </div>\n' +
                '                                                        <div class="form-group">\n' +
                '                                                            <label class="col-sm-2  control-label"></label>\n' +
                '                                                            <div class="col-sm-8">\n' +
                '                                                                <div class="remove btn btn-warning btn-sm pull-right"\n' +
                '                                                                     onclick="$(this).closest(\'.add_class_add\').remove()">\n' +
                '                                                                    <i class="fa fa-trash"></i>&nbsp;Remove\n' +
                '                                                                </div>\n' +
                '                                                            </div>\n' +
                '                                                        </div>\n' +
                '                                                        <hr>\n' +
                '                                                    </div>';
            // newElement.appendTo($("#addproductsizes"));
            $('#sizecount').val( parseInt($('#sizecount').val())+1);
            $('#addproductsizes').append(newElement);
        }

        $(document).ready(function () {
            $(".product_type").on('select2:select', function (e) {
                var data = $(this).val();

                if (data == 1||data == 3||data == 4||data == 5 ||data == 6||data == 7) {

                    $.ajax({
                        method: "get",
                        url: "/vendor/getsizestype/"+data,

                        success: function(result) {
                            $('.selectproductsizes')
                                .empty();
                            $.each(result, function(i, item) {
                                $(".selectproductsizes").append(
                                    $("<option>")
                                        .attr("value", item.id)
                                        .text(item.name_en)
                                );
                            });
                        }
                    });
                    $('[href="#tab-form-3"]').closest('li').show();
                    $('[href="#tab-form-4"]').closest('li').hide();
                } else if (data == 2) {
                    $('[href="#tab-form-3"]').closest('li').hide();
                    $('[href="#tab-form-4"]').closest('li').show();
                } else {
                    $('[href="#tab-form-4"]').closest('li').hide();
                    $('[href="#tab-form-3"]').closest('li').hide();
                }
            });

            $('.select2-hidden-accessible').select2();
        });
        function getSubCategories(parent_id)
        {
var arr =$("#categories").select2("val");
            $.ajax({
                method: "get",
                url: "/vendor/getSubCategories/"+arr,

                success: function(result) {
                    $('#subCategories')
                        .empty();
                    $.each(result, function(i, item) {
                        $("#subCategories").append(
                            $("<option>")
                                .attr("value", item.id)
                                .text(item.name_en)
                        );
                    });
                }
            });
        }
    </script>
@endsection
