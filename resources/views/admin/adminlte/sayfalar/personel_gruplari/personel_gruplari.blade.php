@extends('admin.adminlte.partials.master')
@section('content')
    @section('title', config("const.personel_gruplari").' - '.config("const.yonetim_paneli"))
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{config("const.personel_gruplari")}}</h1>
                </div>
                <div class="col-sm-6">
                    <div class="breadcrumb float-sm-right">
                        <a @if(@kullanicininYetkileri()["personel_gruplari"]["ekle"] == "on") class="btn  ml-2 btn-outline-info" href="{{route("admin.personel_gruplari_ekle.get")}}" @else href="#" title="{{config("const.yetkiniz_yok")}}" class="btn  ml-2 btn-outline-info not-allowed" @endif><i class="fa fa-plus"></i> {{config("const.personel_grubu")}} {{config("const.ekle")}}</a>
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
                            <h3 class="card-title">{{config("const.personel_gruplari")}} {{config("const.listesi")}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Grup Adı</th>
                                    <th>Grup Açıklaması</th>
                                    <th>Üst Grup</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlem</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($personel_gruplari as $personel_grubu)

                                    <tr>
                                        <td>{{$personel_grubu->id}}</td>
                                        <td>{{$personel_grubu->grup_adi}}</td>
                                        <td>{{$personel_grubu->grup_aciklamasi}}</td>
                                        <td>{{$personel_grubu->ust_grup_adi}}</td>
                                        <td>{{date("Y-m-d", strtotime($personel_grubu->created_at))}}</td>
                                        <td><a @if(@kullanicininYetkileri()["personel_gruplari"]["duzenle"] == "on") class="btn  ml-2 btn-outline-warning" href="{{route("admin.personel_gruplari_duzenle.get", ["id" => $personel_grubu->id])}}" @else href="#" title="{{config("const.yetkiniz_yok")}}" class="btn  ml-2 btn-outline-warning not-allowed" @endif><i class="fas fa-pen"></i> {{config("const.duzenle")}}</a><a @if(@kullanicininYetkileri()["personel_gruplari"]["sil"] == "on") class="btn  ml-2 btn-outline-danger btnSil" href="{{route("admin.personel_gruplari_sil.get", ["id" => $personel_grubu->id])}}" @else href="#" title="{{config("const.yetkiniz_yok")}}" class="btn  ml-2 btn-outline-danger not-allowed" @endif><i class="fas fa-trash"></i> {{config("const.sil")}}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Grup Adı</th>
                                    <th>Grup Açıklaması</th>
                                    <th>Üst Grup</th>
                                    <th>Oluşturulma Tarihi</th>
                                    <th>İşlem</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
    <script src="{{asset('assets/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script>
        $(function () {
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "order": [[ 0, 'desc' ]]
            });
        });
    </script>
@endsection

@section('css')
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
@endsection
