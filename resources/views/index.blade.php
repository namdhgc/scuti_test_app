@extends( 'layouts/master' )

@section('title')
    <title>Scuti test app</title>
@endsection

@section('css')
    <style type="text/css">
        .close {
            text-indent: 0px;
        }

        .error {
            color: red;
        }

        textarea {
            max-width: 100%;
        }

        .preview-avatar {
            max-width: 100px;
        }

        .avatar {
            width: 10%;
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

    <input type="hidden" id="route-home-page"   value="{{ URL::Route('get-home-page') }}">
    <input type="hidden" id="route-add-user"    value="{{ URL::Route('post-add-user') }}">
    <input type="hidden" id="route-edit-user"   value="{{ URL::Route('post-edit-user') }}">

    <div class="col-md-12 manager-table">
        <p>This element outside div class="content-data"</p>
        <div class="portlet light box">
            <div class="portlet-title">
                <div class="caption ">
                    <i class="icon-settings "></i>
                    <!-- <span class="caption-subject custom-subject"> Manage table </span> -->
                </div>
                <div class="actions">
                    <a data-toggle="modal" class="btn btn-xs green btn-add-user" title="Add new">
                        <i class="fa fa-plus"></i>
                        Add new
                    </a>
                </div>
            </div>
            <div id="content-data">
                <div class="portlet-body clearfix" id="data-table">
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
                                        <td class="preview-avatar">
                                            <img class="avatar" src="{{ URL::asset( isset( $item->avatar_path ) ? $item->avatar_path : Config::get('system.default_variables.default-image') ) }}">
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
    </div>

    <!-- Modal delete-->
    <div class="modal fade" id="modal-delete" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <form action="#" data-url="{{ URL::Route('post-delete-user') }}" class="form-delete-user" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Form delete user</h4>
                        <input type="hidden" name="id" id="id" value="">
                    </div>
                    <div class="modal-body">
                        <span>
                            <h4>Are you sure to delete this record?</h4>
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-submit-delete-user">
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

    <!-- Modal add-new -->
    <form action="#" method="POST" class="form-add-user form-action" id="form-add-user" enctype="multipart/form-data" >
        <input type="hidden" class="no-clear" id="token" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers"></i>
                        <span class="caption-subject">Form add user</span>
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body clearfix">
                    <div class="form-body">
                        <div class="form-group ">
                            <label for="Name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Name of user">
                        </div>
                        <div class="form-group  ">
                            <label for="address">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Address of user">{{ old('address') }}</textarea>
                        </div>
                        <div class="form-group ">
                            <label for="code">Age</label>
                            <input type="text" class="form-control" id="age" name="age" value="{{ old('age') }}" placeholder="Age of user">
                        </div>
                        <div class="form-group ">
                            <label for="code">Avatar</label>
                            <input type="file" name="avatar" class="form-control" id="avatar">
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success btn-submit-add-user">Add new</button>
                                <a href="javascript:;" class="btn default btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End modal add-new -->


</div>
@endsection
