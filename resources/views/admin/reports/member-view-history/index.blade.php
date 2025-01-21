@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <h2 class="text-dark"> Staff Wise View History Report </h2>
        </div>
        <div class="col-sm-12 col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if (Sentinel::inRole('admin'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @elseif(Sentinel::inRole('manager'))
                        <li class="breadcrumb-item"><a href="{{ route('manager.dashboard') }}">Dashboard</a></li>
                    @elseif(Sentinel::inRole('employee'))
                        <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Dashboard</a></li>
                    @endif
                    <li class="breadcrumb-item"><a href="#">Staff Wise View History</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        {!! Form::open(['route' => 'member-history.export', 'method' => 'post']) !!}
        <div class="row">
            <div class="col-sm-12 col-md-12">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start">Start Date:</label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" name="start_date" class="form-control" id="start_date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end">End Date:</label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" name="end_date" class="form-control" id="end_date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Staff:</label>
                        {!! Form::select('member', [], null, [
                            'placeholder' => 'select Staff',
                            'class' => 'form-control',
                            'id' => 'member',
                        ]) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label style="width:100%;">&nbsp;</label>
                        <button class="btn btn-success" id="search-item"> Search </button>
                        <button class="btn btn-danger" id="reset-item"> Reset </button>
                        <button class="btn btn-warning" id="export-item"> Export </button>
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
        <div class="row">
            <div class="col-sm-12 col-md-12">
                @include('admin.reports.member-view-history.table')
            </div>
        </div>
    </div>
@stop

@push('page_css')
    <link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
    <link href="{{ asset('dark/assets/plugins/select2/dist/css/select2.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('page_script')
    <script type="text/javascript" src="{{ asset('dark/assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}"></script>
    <script>
        $(function() {
            var user = '{!! $user !!}';
            var start_date = '{!! Date('d-m-Y', strtotime($start_date)) !!}';
            var end_date = '{!! Date('d-m-Y', strtotime($end_date)) !!}';
            if (user) {
                var parse_json = JSON.parse(user);
                var member_name = `${parse_json.first_name}`;
                var member_id = parse_json.id;
                var newOption = new Option(member_name, member_id, true, true);
                $('#member').append(newOption).trigger('change');
            }
            $("#start_date").val(start_date);
            $("#end_date").val(end_date);

            $("#member").select2({
                ajax: {
                    url: '{!! route('member-history.get-member-dropdown') !!}',
                    data: function(params) {
                        return {
                            q: params.term
                        }
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        });


        function collapse(cell) {
            var row = cell.parentElement;
            var target_row = row.parentElement.children[row.rowIndex + 1];
            if (target_row.style.display == 'table-row') {
                cell.innerHTML = '<span class="btn shadow btn-primary">+</span>';
                target_row.style.display = 'none';
            } else {
                cell.innerHTML = '<span class="btn shadow btn-info">-</span>';
                target_row.style.display = 'table-row';
            }
        }

        var table = $('#memberViewHistoryTable').DataTable({
            aaSorting: [
                [0, 'desc']
            ],
            responsive: true,
            processing: true,
            pageLength: -1,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            serverSide: true,
            searching: true,
            paging: true,
            info: true,
            bSort: false,
            ajax: {
                url: '{!! route('member-history.datatable') !!}',
                dataType: 'json',
                data: function(d) {
                    // Passing additional data to the server-side
                    d.member = $("#member").val();
                    d.start_date = $("#start_date").val();
                    d.end_date = $("#end_date").val();
                }, error: function(xhr, error, code) {
            if (xhr.status === 404) {
             $("#error-message").html(xhr.responseJSON.msg);
             $("#error-modal").modal('show');
             $("#reset-item").click();
            }
            }
            },
            columns: [{
                    data: 'member_id',
                    name: 'member_id'
                },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'designation',
                    name: 'designation'
                },
                {
                    data: 'checkouts_count',
                    name: 'checkouts_count'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ]
        });

        $("#search-item").click(function(e) {
            e.preventDefault();
            $('#memberViewHistoryTable').DataTable().clear().draw();
        });

        $("#reset-item").click(function(e) {
            e.preventDefault();
            $("#member").val('').trigger('change');
            var start_date = new Date();
            start_date.setDate(start_date.getDate() - 7);
            $("#start_date").datepicker("setDate", start_date);
            var end_date = new Date();
            $("#end_date").datepicker("setDate", end_date);
            $('#memberViewHistoryTable').DataTable().clear().draw();
        });
    </script>
@endpush
