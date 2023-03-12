@extends('admin.adminlte.partials.master')
@section('content')
    @section('title', config("const.kullanicilar").' - '.config("const.yetkiler").' - '.config("const.yonetim_paneli"))
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{config("const.kullanici")}} {{config("const.yetkileri")}}</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a href="{{route("admin.kullanicilar.get")}}" class="btn btn-info"><i class="fa fa-users"></i> {{config("const.kullanicilar")}}</a>
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
                            <h3 class="card-title"><i class="fas fa-lock"></i> #{{$kullanici->id}} {{config("const.kullanici")}} {{config("const.yetkileri")}}</h3>
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
                                                <div class="col-auto border-right mt-1">
                                                    {{config("const.goruntule")}}: <input type="checkbox" @if(@$yetkiler["kullanicilar"]["goruntule"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="goruntule" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                </div>
                                                    <div class="col-auto border-right mt-1">
                                                        {{config("const.ekle")}}: <input type="checkbox" @if(@$yetkiler["kullanicilar"]["ekle"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="ekle" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                <div class="col-auto border-right mt-1">
                                                    {{config("const.duzenle")}}: <input type="checkbox"  @if(@$yetkiler["kullanicilar"]["duzenle"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="duzenle" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                </div>
                                                <div class="col-auto border-right mt-1">
                                                    {{config("const.sil")}}: <input type="checkbox"  @if(@$yetkiler["kullanicilar"]["sil"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="sil" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                </div>
                                                    <div class="col-auto mt-1">
                                                        {{config("const.yetkiler")}}: <input type="checkbox"  @if(@$yetkiler["kullanicilar"]["yetkiler"] == "on") checked @endif name="my-checkbox" data-menu="kullanicilar" data-alt-menu="yetkiler" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                </div>
                                                </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>{{config('const.personeller')}}</td>
                                        <td>
                                            <div class="container"><div class="row">
                                                    <div class="col-auto border-right mt-1">
                                                        {{config("const.goruntule")}}: <input type="checkbox" @if(@$yetkiler["personeller"]["goruntule"] == "on") checked @endif name="my-checkbox" data-menu="personeller" data-alt-menu="goruntule" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto border-right mt-1">
                                                        {{config("const.ekle")}}: <input type="checkbox" @if(@$yetkiler["personeller"]["ekle"] == "on") checked @endif name="my-checkbox" data-menu="personeller" data-alt-menu="ekle" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto border-right mt-1">
                                                        {{config("const.duzenle")}}: <input type="checkbox"  @if(@$yetkiler["personeller"]["duzenle"] == "on") checked @endif name="my-checkbox" data-menu="personeller" data-alt-menu="duzenle" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto mt-1">
                                                        {{config("const.sil")}}: <input type="checkbox"  @if(@$yetkiler["personeller"]["sil"] == "on") checked @endif name="my-checkbox" data-menu="personeller" data-alt-menu="sil" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{config('const.personel_gruplari')}}</td>
                                        <td>
                                            <div class="container"><div class="row">
                                                    <div class="col-auto border-right mt-1">
                                                        {{config("const.goruntule")}}: <input type="checkbox" @if(@$yetkiler["personel_gruplari"]["goruntule"] == "on") checked @endif name="my-checkbox" data-menu="personel_gruplari" data-alt-menu="goruntule" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto border-right mt-1 ">
                                                        {{config("const.ekle")}}: <input type="checkbox" @if(@$yetkiler["personel_gruplari"]["ekle"] == "on") checked @endif name="my-checkbox" data-menu="personel_gruplari" data-alt-menu="ekle" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto border-right mt-1">
                                                        {{config("const.duzenle")}}: <input type="checkbox"  @if(@$yetkiler["personel_gruplari"]["duzenle"] == "on") checked @endif name="my-checkbox" data-menu="personel_gruplari" data-alt-menu="duzenle" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto mt-1">
                                                        {{config("const.sil")}}: <input type="checkbox"  @if(@$yetkiler["personel_gruplari"]["sil"] == "on") checked @endif name="my-checkbox" data-menu="personel_gruplari" data-alt-menu="sil" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{config('const.site_ayarlari')}}</td>
                                        <td>
                                            <div class="container"><div class="row">
                                                    <div class="col-auto border-right">
                                                        {{config("const.goruntule")}}: <input type="checkbox" @if(@$yetkiler["site_ayarlari"]["goruntule"] == "on") checked @endif name="my-checkbox" data-menu="site_ayarlari" data-alt-menu="goruntule" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div><div class="col-auto mt-1 border-right">
                                                        {{config("const.temel_bilgiler")}}: <input type="checkbox" @if(@$yetkiler["site_ayarlari"]["temel_bilgiler"] == "on") checked @endif name="my-checkbox" data-menu="site_ayarlari" data-alt-menu="temel_bilgiler" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto mt-1 border-right">
                                                        {{config("const.site_resimleri")}}: <input type="checkbox" @if(@$yetkiler["site_ayarlari"]["site_resimleri"] == "on") checked @endif name="my-checkbox" data-menu="site_ayarlari" data-alt-menu="site_resimleri" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto mt-1 border-right">
                                                        {{config("const.iletisim_bilgileri")}}: <input type="checkbox" @if(@$yetkiler["site_ayarlari"]["iletisim_bilgileri"] == "on") checked @endif name="my-checkbox" data-menu="site_ayarlari" data-alt-menu="iletisim_bilgileri" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
                                                    </div>
                                                    <div class="col-auto  mt-1">
                                                        {{config("const.sosyal_medya")}}: <input type="checkbox" @if(@$yetkiler["site_ayarlari"]["sosyal_medya"] == "on") checked @endif name="my-checkbox" data-menu="site_ayarlari" data-alt-menu="sosyal_medya" data-bootstrap-switch data-on-text="{{config("const.aktif")}}" data-off-text="{{config("const.pasif")}}" data-off-color="danger" data-on-color="success">
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
