@extends('admin.adminlte.partials.master')
@section('content')
    @section('title', config("const.personel_gruplari").' - '.config("const.duzenle").' - '.config("const.yonetim_paneli"))
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{config("const.personel_grubu")}} {{config("const.duzenle")}}</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a href="{{route("admin.personel_gruplari.get")}}" class="btn btn-info"><i
                                class="fa fa-users"></i> {{config("const.personel_gruplari")}}</a>
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
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i
                                    class="fas fa-user-plus"></i> #{{$get_personel_grubu["id"]}} {{config("const.personel_grubu")}} {{config("const.duzenle")}}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- form start -->
                        <form id="yeniPersonelGrubuEkle" method="post"
                              action="{{route("admin.personel_gruplari_duzenle.post", ["id" => $get_personel_grubu["id"]])}}">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="forName">Grup Adı</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" name="grup_adi" class="form-control" id="forName"
                                               placeholder="Grup Adı" value="{{old("grup_adi", $get_personel_grubu["grup_adi"])}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="forEmail">Grup Açıklaması</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                        </div>
                                        <input type="text" name="grup_aciklamasi" class="form-control" id="forEmail"
                                               placeholder="Grup Açıklaması" value="{{old("grup_aciklamasi", $get_personel_grubu["grup_aciklamasi"])}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Üst Grup</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                                        </div>
                                    <select class="form-control select2"  name="ust_grup">
                                        <option @if(old("ust_grup",$get_personel_grubu["ust_grup"]) == 0) selected="selected" @endif value="0">Ana Grup</option>
                                        @foreach($personel_gruplari as $personel_grubu)
                                            <option @if(old("ust_grup",$get_personel_grubu["ust_grup"]) == $personel_grubu->id) selected="selected" @endif value="{{$personel_grubu->id}}">{{$personel_grubu->grup_adi}}</option>
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
                    grup_adi: {
                        required: true,
                        minlength: 3,
                    },
                    ust_grup: {
                        required: true,
                        pattern: /^[0-9]+$/,
                    }
                },
                messages: {
                    grup_adi: {
                        required: "Grup adı zorunludur",
                        minlength: "Grup adı en az 3 karakter uzunluğunda olmalıdır"
                    },
                    ust_grup: {
                        required: "Üst Grup zorunludur",
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

