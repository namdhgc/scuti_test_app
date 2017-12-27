@extends( 'layouts/master' )

@section('title')
    <title>Scuti test app</title>
@endsection

@section('css')
    <style type="text/css">
        .close {
            text-indent: 0px;
        }
    </style>
@endsection

@section('js')

    <script src="{{ URL::asset('js/manage/user.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        User.init();
    </script>

@endsection

@section('content')
<div class="row">
    <input type="hidden" id="route-home-page" value="{{ URL::Route('get-home-page') }}">
    <div class="col-md-12 manager-table">
        <div class="portlet light custom-portlet box">
            <div class="portlet-title custom-portlet-title-dark">
                <div class="caption ">
                    <i class="icon-settings custom-icon-dark"></i>
                    <!-- <span class="caption-subject custom-subject"> Manage table </span> -->
                </div>
                <div class="actions">
                    <a data-toggle="modal" data-from-action="form-add-new" class="btn btn-xs btn-add-new" title="Add new">
                        <i class="fa fa-plus"></i>
                        Add new
                    </a>
                </div>
            </div>
            <div class="portlet-body clearfix" id="dataTable">
                <div class="table-toolbar">
                    <div class="row">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover order-column" id="">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Age</th>
                                <th>Avatar</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if( isset( $data ) )
                                @foreach( $data['response'] as $key => $item )
                                <tr class="odd gradeX"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-address="{{ $item->address }}"
                                        data-age="{{ $item->age }}" >

                                    <td class="id">
                                        {{ $item->id }}
                                    </td>
                                    <td class="name">
                                        {{ $item->name }}
                                    </td>
                                    <td class="address">
                                        {{ $item->address }}
                                    </td>
                                    <td class="age">
                                        {{ $item->age }}
                                    </td>
                                    <td>
                                    <!-- avatar -->
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="btn btn-xs blue btn-edit" data-from-action="form-edit">
                                            <i class="fa fa-edit"></i>
                                            Edit
                                        </a>
                                        <a href="javascript:;" class="btn btn-xs red btn-delete" data-id="{{ $item->id }}" data-from-delete="form-delete">
                                            <i class="fa fa-times"></i>
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <center>
                    {{ $data['response']->render() }}
                </center>
            </div>
        </div>
    </div>

    <!-- Modal delete-->
    <div class="modal fade" id="modal-delete" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="#" data-url="{{ URL::Route('post-delete-user') }}" class="form-delete" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Form delete</h4>
                        <input type="hidden" name="id" id="id" value="">
                    </div>
                    <div class="modal-body">
                        <span>
                            <h4>Are you sure to delete this record?</h4>
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger btn-submit">
                            Delete
                        </button>
                        <button type="button" class="btn default btn-cancel" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->
</div>
@endsection
