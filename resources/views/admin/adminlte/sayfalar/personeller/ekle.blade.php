@extends('admin.adminlte.partials.master')
@section('content')
    @section('title', config("const.personeller").' - '.config("const.ekle").' - '.config("const.yonetim_paneli"))
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{config("const.personel")}} {{config("const.ekle")}}</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a href="{{route("admin.personeller.get")}}" class="btn btn-info"><i
                                class="fa fa-user"></i> {{config("const.personeller")}}</a>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-12">
                    @include('admin.adminlte.widgets.alert')
                </div>


                <div class="col-12">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i
                                    class="fas fa-user-plus"></i> {{config("const.yeni")}} {{config("const.personel")}} {{config("const.ekle")}}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- form start -->
                        <form id="yeniPersonelGrubuEkle" method="post"  enctype="multipart/form-data"
                              action="{{route("admin.personeller_ekle.post")}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="exampleInputFile">Resim</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" accept=".png,.jpg,.jpeg" name="resim" class="custom-file-input" id="exampleInputFile">
                                            <label class="custom-file-label" for="exampleInputFile">Dosya seçiniz</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forName">Adı</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" name="ad" class="form-control" id="forName"
                                               placeholder="Ad" value="{{old("ad")}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forEmail">Soyadı</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                        </div>
                                        <input type="text" name="soyad" class="form-control" id="forEmail"
                                               placeholder="Soyadı" value="{{old("soyad")}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Grup</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                                        </div>
                                    <select class="form-control select2"  name="grup">
                                        @foreach($personel_gruplari as $personel_grubu)
                                            <option @if(old("grup") == $personel_grubu->id) selected @endif value="{{$personel_grubu->id}}">{{$personel_grubu->grup_adi}}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success"><i
                                        class="fas fa-save"></i> {{config("const.kaydet")}}</button>
                            </div>
                        </form>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <script src="{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- jquery-validation -->
    <script src="{{asset('assets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-validation/additional-methods.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(function () {

            $('form').on('submit', function (event) {
                // Formda hala geçerli olan validation kurallarını kontrol edin
                if ($(this).valid()) {
                    // Geçerli ise form submit işlemini gerçekleştirin
                    var submitButton = $(this).find('button[type="submit"]');
                    submitButton.prop("disabled", true);
                    submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{config("const.kaydediliyor")}}');
                }
            });
            $('#yeniPersonelGrubuEkle').validate({
                rules: {
                    ad: {
                        required: true,
                        minlength: 3,
                    },
                    soyad: {
                        required: true,
                        minlength: 3,
                    },
                    resim: {
                        required: true,
                        accept: "image/png,image/jpeg,image/jpg",
                        maxsize: 1024 * 1024 * 10, // 10 MB
                    },
                    grup: {
                        required: true,
                        pattern: /^[0-9]+$/,
                    }
                },
                messages: {
                    ad: {
                        required: "Adı zorunludur",
                        minlength: "Adı en az 3 karakter uzunluğunda olmalıdır"
                    },
                    resim: {
                        required: "Resim zorunludur",
                        accept: "Resim dosyası seçiniz. (.png, .jpg, .jpeg)",
                        maxsize: "Resim dosyası 10 MB'dan büyük olamaz"
                    },
                    soyad: {
                        required: "Soyadı zorunludur",
                        minlength: "Soyadı en az 3 karakter uzunluğunda olmalıdır"
                    },
                    grup: {
                        required: "Grup zorunludur",
                        pattern: "Sayısal değer giriniz"
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $("input[data-bootstrap-switch]").each(function () {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });

            $('.select2').select2({
                theme: 'bootstrap4'
            });
        })
    </script>
@endsection

@section('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection

