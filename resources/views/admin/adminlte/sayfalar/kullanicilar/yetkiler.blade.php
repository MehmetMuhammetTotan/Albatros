@extends('admin.adminlte.partials.master')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>#{{$kullanici->id}} Kullanıcının Yetkileri</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a href="{{route("admin.kullanicilar.get")}}" class="btn btn-info"><i class="fa fa-users"></i> Kullanıcılar</a>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-lock"></i> #{{$kullanici->id}} Kullanıcının Yetkileri</h3>
                        </div>
                        <!-- /.card-header -->

                        @php $yetkiler = json_decode($yetkiler["yetkiler"], true); @endphp
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Menü Adı</th>
                                    <th>Yetkileri</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{config('const.kullanicilar')}}</td>
                                        <td>
                                            <div class="container"><div class="row">
                                                <div class="col-auto border-right">
                                                    Görüntüle: <input type="checkbox" @if(@$yetkiler["kullanicilar"]["goruntule"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="goruntule" data-bootstrap-switch data-on-text="AKTİF" data-off-text="PASİF" data-off-color="danger" data-on-color="success">
                                                </div>
                                                    <div class="col-auto border-right">
                                                        Ekle: <input type="checkbox" @if(@$yetkiler["kullanicilar"]["ekle"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="ekle" data-bootstrap-switch data-on-text="AKTİF" data-off-text="PASİF" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                <div class="col-auto border-right">
                                                    Yetkiler: <input type="checkbox"  @if(@$yetkiler["kullanicilar"]["yetkiler"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="yetkiler" data-bootstrap-switch data-on-text="AKTİF" data-off-text="PASİF" data-off-color="danger" data-on-color="success">
                                                </div>

                                                <div class="col-auto border-right">
                                                    Düzenle: <input type="checkbox"  @if(@$yetkiler["kullanicilar"]["duzenle"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="duzenle" data-bootstrap-switch data-on-text="AKTİF" data-off-text="PASİF" data-off-color="danger" data-on-color="success">
                                                </div>

                                                <div class="col-auto">
                                                    Sil: <input type="checkbox"  @if(@$yetkiler["kullanicilar"]["sil"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="sil" data-bootstrap-switch data-on-text="AKTİF" data-off-text="PASİF" data-off-color="danger" data-on-color="success">
                                                </div>
                                                </div>
                                                </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Menü Adı</th>
                                    <th>Yetkileri</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
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
    <script>
        $(function () {

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });


            $('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', switchChange);
            function switchChange(event, state) {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                $.ajax({
                    url: "{{route("admin.kullanicilar_yetkiler_aktif_pasif.post", ["id" => $kullanici->id])}}",
                    type: "POST",
                    data: {
                        _token: "{{csrf_token()}}",
                        menu: $(this).data("menu"),
                        alt_menu: $(this).data("alt-menu"),
                        durum: $(this).prop('checked')
                    },
                    success: function (response) {
                        response = JSON.parse(response);
                        if(response.status){
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            })
                        }else{
                            event.bootstrapSwitch('state', false);
                            Toast.fire({
                                icon: 'error',
                                title: response.message
                            })
                        }
                    }
                });
            }

        })
    </script>
@endsection

@section('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection
