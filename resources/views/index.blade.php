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
            max-height: 200px;
        }

        .preview-avatar {
            min-width: 20px;
            max-width: 40px;
        }

        .view-avatar {
            width: 100%;
        }

        .hide-form {
            display: none;
        }

        .page-content {
            padding: 25px 20px 10px;
        }

        .view-address {
             min-height: 70px;
        }

        .clear-input-file {
            margin-top: 5px;
        }

        .view-address {
            word-wrap: break-word;
            width: 100%;
            padding: 6px 12px;
            background-color: #fff;
            border: 1px solid #c2cad8;
        }

        .form-view-left {
            padding-left: 0px;
            padding-right: 0px;
        }

        .required-character {
            color: red;
        }

        .tooltip-icon .tooltip-text {
            width: 120px;
            background-color: #2a3035;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            border-radius: 10px !important;
            /* Position the tooltip */
            position: absolute;
            z-index: 1;
        }

        .tooltip-text {
            visibility: hidden;
        }

        .tooltip-icon:hover .tooltip-text {
            visibility: visible;
        }

    </style>
@endsection

@section('js')

    <script src="{{ URL::asset('js/manage/user.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        User.init();
    </script>

@endsection

@section('content')
<div class="row">

    <input type="hidden" id="route-home-page"           value="{{ URL::Route('get-home-page') }}">
    <input type="hidden" id="route-add-user"            value="{{ URL::Route('add-user') }}">
    <input type="hidden" id="route-edit-user"           value="{{ URL::Route('edit-user') }}">
    <input type="hidden" id="route-sort-pagination"     value="{{ URL::Route('sort-pagination') }}">
    <input type="hidden" id="current-page"              value="{{ COUNT( $data['response'] ) > 0 ? $data['response']->currentPage() : ''  }}">

    <div class="col-md-12 manager-table">
        <div class="portlet light box">
            <div class="portlet-title">
                <div class="caption ">
                    <div class="form-group">
                        <label for="sort_by">Sort 
                            <i id="sort-type-icon" class=""></i>
                        </label>
                        <select class="form-control" id="sort_data">
                            <option value=""                data-sort-by=""         data-sort-type="">None</option>
                            <option value="name_asc"        data-sort-by="name"     data-sort-type="ASC"> Name Ascending</option>
                            <option value="name_desc"       data-sort-by="name"     data-sort-type="DESC">Name Descending</option>
                            <option value="address_asc"     data-sort-by="address"  data-sort-type="ASC"> Address Ascending</option>
                            <option value="address_desc"    data-sort-by="address"  data-sort-type="DESC">Address Descending</option>
                            <option value="age_asc"         data-sort-by="age"      data-sort-type="ASC"> Age Ascending</option>
                            <option value="age_desc"        data-sort-by="age"      data-sort-type="DESC">Age Descending</option>
                        </select>
                    </div>
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
                                            data-age="{{ $item->age }}"
                                            data-avatar="{{ URL::asset( isset( $item->avatar_path ) ? $item->avatar_path : Config::get('system.default_variables.default-image') ) }}" >

                                        <td class="id">
                                            {{ $item->id }}
                                        </td>
                                        <td class="name">
                                            @if ( strlen( $item->name ) > 40 )
                                                {{ mb_substr( $item->name, 0, 40 ) . '...' }}
                                            @else
                                                {{ $item->name }}
                                            @endif
                                        </td>
                                        <td class="address">
                                            @if ( strlen( $item->address ) > 40 )
                                                {{ mb_substr( $item->address, 0, 40 ) . '...' }}
                                            @else
                                                {{ $item->address }}
                                            @endif
                                        </td>
                                        <td class="age">
                                            {{ $item->age }}
                                        </td>
                                        <td class="preview-avatar">
                                            <img class="preview-avatar" src="{{ URL::asset( isset( $item->avatar_path ) ? $item->avatar_path : Config::get('system.default_variables.default-image') ) }}">
                                        </td>
                                        <td>
                                            <a href="javascript:;" class="btn btn-xs green btn-view">
                                                <i class="fa fa-edit"></i>
                                                View
                                            </a>
                                            <a href="javascript:;" class="btn btn-xs blue btn-edit">
                                                <i class="fa fa-edit"></i>
                                                Edit
                                            </a>
                                            <a href="javascript:;" class="btn btn-xs red btn-delete" data-id="{{ $item->id }}">
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
                        <?php
                            $link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
                        ?>

                        @if ($data['response']->lastPage() > 1)
                            <ul class="pagination">
                                <li class="{{ ($data['response']->currentPage() == 1) ? ' disabled' : '' }}" >
                                    <a href="{{ $data['response']->url(1) }}" class="pagination-link" data-page="1">First</a>
                                 </li>
                                @for ($i = 1; $i <= $data['response']->lastPage(); $i++)
                                    <?php
                                    $half_total_links   = floor($link_limit / 2);
                                    $from               = $data['response']->currentPage() - $half_total_links;
                                    $to                 = $data['response']->currentPage() + $half_total_links;
                                    if ( $data['response']->currentPage() < $half_total_links ) {
                                       $to += $half_total_links - $data['response']->currentPage();
                                    }
                                    if ( $data['response']->lastPage() - $data['response']->currentPage() < $half_total_links ) {
                                        $from -= $half_total_links - ($data['response']->lastPage() - $data['response']->currentPage()) - 1;
                                    }
                                    ?>
                                    @if ($from < $i && $i < $to)
                                        <li class="{{ ($data['response']->currentPage() == $i) ? ' active' : '' }} {{ $i }}">
                                            <a href="{{ $data['response']->url($i) }}" class="pagination-link" data-page="{{ $i }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endif
                                @endfor
                                <li class="{{ ($data['response']->currentPage() == $data['response']->lastPage()) ? ' disabled' : '' }}">
                                    <a href="{{ $data['response']->url($data['response']->lastPage()) }}"
                                        class="pagination-link"
                                        data-page="{{ $data['response']->lastPage() }}">
                                        Last
                                    </a>
                                </li>
                            </ul>
                        @endif
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
                <form action="#" data-url="{{ URL::Route('delete-user') }}" class="form-delete-user" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete user</h4>
                        <input type="hidden" name="id" id="id" value="">
                    </div>
                    <div class="modal-body">
                        <span>
                            <h4>Are you sure to delete this user?</h4>
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
    <form action="#" method="POST" class="form-add-user form-action hide-form" id="form-add-user" enctype="multipart/form-data" >
        <input type="hidden" class="no-clear" id="token" name="_token" value="{{ csrf_token() }}">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers"></i>
                        <span class="caption-subject">Add user</span>
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body clearfix">
                    <div class="form-body">
                        <div class="form-group ">
                            <label for="Name">Name <span class="required-character">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Name of user">
                        </div>
                        <div class="form-group  ">
                            <label for="address">Address <span class="required-character">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Address of user">{{ old('address') }}</textarea>
                        </div>
                        <div class="form-group ">
                            <label for="age">Age <span class="required-character">*</span></label>
                            <input type="text" class="form-control" id="age" name="age" value="{{ old('age') }}" placeholder="Age of user">
                        </div>
                        <div class="form-group ">
                            <label for="avatar">Avatar</label>
                            <input type="file" name="avatar" class="avatar" id="avatar" accept="image/gif, image/jpeg, image/png">
                            <button type="button" class="btn btn-warning clear-input-file">Clear file</button>
                            <i class="tooltip-icon fa fa-info-circle">
                                <span class="tooltip-text">Upload max file size is 10Mb</span>
                            </i>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success btn-submit-add-user">Add new</button>
                                <a href="javascript:;" class="btn btn-danger btn-reset">Reset</a>
                                <a href="javascript:;" class="btn default btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End modal add-new -->


    <!-- Modal edit -->
    <form action="#" method="POST" class="form-edit-user form-action hide-form" id="form-edit-user" enctype="multipart/form-data" >
        <input type="hidden" class="no-clear" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="">
        <div class="col-md-12">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers"></i>
                        <span class="caption-subject">Edit user</span>
                    </div>
                    <div class="actions">
                    </div>
                </div>
                <div class="portlet-body clearfix">
                    <div class="form-body">
                        <div class="form-group ">
                            <label for="Name">Name <span class="required-character">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Name of user">
                        </div>
                        <div class="form-group  ">
                            <label for="address">Address <span class="required-character">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="Address of user">{{ old('address') }}</textarea>
                        </div>
                        <div class="form-group ">
                            <label for="age">Age <span class="required-character">*</span></label>
                            <input type="text" class="form-control" id="age" name="age" value="{{ old('age') }}" placeholder="Age of user">
                        </div>
                        <div class="form-group ">
                            <label for="avatar">Avatar</label>
                            <input type="file" name="avatar" class="avatar" id="avatar" accept="image/gif, image/jpeg, image/png">
                            <button type="button" class="btn btn-warning clear-input-file">Clear file</button>
                            <i class="tooltip-icon fa fa-info-circle">
                                <span class="tooltip-text">Upload max file size is 10Mb</span>
                            </i>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success btn-submit-edit-user">Save</button>
                                <a href="javascript:;" class="btn btn-danger btn-reset-edit-form">Reset</a>
                                <a href="javascript:;" class="btn default btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End modal edit -->

    <!-- Modal view -->
    <form action="#" method="POST" class="form-view-user form-action hide-form" id="form-view-user" enctype="multipart/form-data" >
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class=" icon-layers"></i>
                    <span class="caption-subject">View user</span>
                </div>
                <div class="actions">
                </div>
            </div>
            <div class="portlet-body clearfix">
                <div class="form-body">
                    <div class="col-md-8 form-view-left">
                        <div class="form-group ">
                            <label for="name">Name</label>
                            <label type="text" class="view-address" id="name" name="name" value=""></label>
                        </div>
                        <div class="form-group  ">
                            <label for="address">Address</label>
                            <label class="view-address" id="address" name="address"></label>
                        </div>
                        <div class="form-group ">
                            <label for="age">Age</label>
                            <label type="text" class="form-control" id="age" name="age" value=""></label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group ">
                            <img class="view-avatar" id="avatar" src=""/>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <div class="form-group">
                        <a href="javascript:;" class="btn default btn-cancel">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- End modal view -->

</div>
@endsection
