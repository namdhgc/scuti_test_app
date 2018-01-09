<?php

namespace App\Http\Controllers;
use Input;
use Auth;
use Config;
use Session;
use Cache;
use Lang;
use Image;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\User as ModelUser;
use App\Http\Controllers\Controller;
use App\Http\Response\Response;

/**
*-----------------------------------------------------------------------------
* UserController
*-----------------------------------------------------------------------------
*
* @since 26/12/2017 11:51:00
*
* @author NamDH
*
*/
define('LIMIT_RECORD_PER_PAGE',  5);
define('UPLOAD_MAX_FILE_SIZE',  10485760); // 10 * 1024 * 1024 = 10Mb

class UserController extends Controller
{
    public $ALLOWED_MIME_TYPES = [ 'image/jpeg', 'image/jpg', 'image/png', 'image/gif' ];

    /**
    *-----------------------------------------------------------------------------
    * getData
    *-----------------------------------------------------------------------------
    *
    * Action getData for screen manage-user
    * Accept sort, limit, sort_type, key_search
    *
    * @param :
    *
    * @return :
    *          + Array response
    */
    public function getData()
    {
        $ModelUser  = new ModelUser();
        $limit      = LIMIT_RECORD_PER_PAGE;
        $offset     = null;
        $selectType = Config::get('system.type.query.paginate');
        $fields     = null;
        $column     = [];
        $sort_by    = Input::get('sort_by');
        $sort_type  = Input::get('sort_type');
        $selected_page  = Input::get('selected_page');

        if ( !isset($sort_by) || !isset($sort_type) ) {
            $sort_by    = 'created_time';
            $sort_type  = 'DESC';
        }

        $where      = [
            [
                'fields'    => 'deleted_time',
                'operator'  => 'null',
            ]
        ];

        $order      = [
            [
                'fields'    => $sort_by,
                'operator'  => $sort_type
            ]
        ];

        $results = $ModelUser->select( $where, $limit, $offset, $selectType, $order, $fields, $column, $selected_page  );

        return view('index')->with( 'data', $results );
    }

    public function insertData()
    {
        $ModelUser      = new ModelUser();
        $Response       = new Response();
        $results        = $Response->response(200,'','',true);
        $name           = Input::get('name');
        $address        = Input::get('address');
        $age            = Input::get('age');
        $avatar         = Input::file('avatar');
        $created_time   = strtotime( \Carbon\Carbon::now()->toDateTimeString() );

        $data = [
            'name'          => $name,
            'address'       => $address,
            'age'           => $age,
            'created_time'  => $created_time,
        ];

        $validator = Validator::make(
            [
                'name'      => $name,
                'address'   => $address,
                'age'       => $age,
            ],
            [
                'name'      => 'required | max:100',
                'address'   => 'required | max:300',
                'age'       => 'required | numeric',
            ]
        );

        if ( $validator->fails() ) {
            // The given data did not pass validation
            $keys       = $validator->errors()->keys();
            $messages   = $validator->errors()->getMessages();
            $msg        = '';

            foreach ($keys as $index => $key) {
                
                $msg .= $key . ': ' . $messages[$key][0] . "<br>";
            }
            $results['meta']['code']    = '0005';
            $results['meta']['success'] = false;
            $results['meta']['msg']     = $msg;
        } else {
            if ( !empty( $avatar ) ) {
                $file_name      = $avatar->getClientOriginalName();
                $file_extension = $avatar->getClientOriginalExtension();
                $file_real_path = $avatar->getRealPath();
                $file_mime      = $avatar->getMimeType();
                $file_size      = $avatar->getClientSize();

                if ( $file_size > UPLOAD_MAX_FILE_SIZE ) {
                    // file size too large
                    $results['meta']['code']    = '0006';
                    $results['meta']['success'] = false;
                    $results['meta']['msg']     = Lang::get('message.web.error.0006');
                    
                    return response()->json($results);
                }

                // image mime types start with "image/"
                if( in_array($file_mime, $this->ALLOWED_MIME_TYPES) ) {
                    // this is an image
                    // move file to public path
                    $full_path_file = 'upload/' . $file_name;
                    $avatar->move( public_path() . '/upload', $file_name );
                    $data['avatar_path'] = $full_path_file;
                } else {
                    // this is not an image
                    $results['meta']['code']    = '0004';
                    $results['meta']['success'] = false;
                    $results['meta']['msg']     = Lang::get('message.web.error.0004');
                    
                    return response()->json($results);
                }
            }

            $added_record = $ModelUser->insert( $data );

            if ( $added_record['meta']['success'] ) {
                // insert successfull
                $results['response']        = $added_record['response'];
                $results['meta']['msg']     = Lang::get('message.web.success.0001');
            } else {
                $results['meta']['code']    = '0003';
                $results['meta']['success'] = false;
                $results['meta']['msg']     = Lang::get('message.web.error.0003');
            }
        }

        return response()->json($results);
    }

    public function updateData()
    {
        $ModelUser      = new ModelUser();
        $Response       = new Response();
        $results        = $Response->response(200,'','',true);
        $id             = Input::get('id');
        $name           = Input::get('name');
        $address        = Input::get('address');
        $age            = Input::get('age');
        $avatar         = Input::file('avatar');
        $updated_time   = strtotime( \Carbon\Carbon::now()->toDateTimeString() );
        $where          = [
            [
                'fields'    => 'id',
                'operator'  => '=',
                'value'     => $id
            ]
        ];
        $check_id       = $ModelUser->checkExistsData( $where );

        if ( COUNT( $check_id['response'] ) > 0 ) {
            $data = [
                'name'          => $name,
                'address'       => $address,
                'age'           => $age,
                'updated_time'  => $updated_time,
            ];

            $where = [
                [
                    'fields'    => 'id',
                    'operator'  => '=',
                    'value'     => $id
                ]
            ];

            $validator = Validator::make(
                [
                    'id'        => $id,
                    'name'      => $name,
                    'address'   => $address,
                    'age'       => $age,
                ],
                [
                    'id'        => 'required | numeric',
                    'name'      => 'required | max:100',
                    'address'   => 'required | max:300',
                    'age'       => 'required | numeric',
                ]
            );

            if ( $validator->fails() ) {
                // The given data did not pass validation
                $keys       = $validator->errors()->keys();
                $messages   = $validator->errors()->getMessages();
                $msg        = '';

                foreach ($keys as $index => $key) {
                    
                    $msg .= $key . ': ' . $messages[$key][0] . "<br>";
                }
                $results['meta']['code']    = '0005';
                $results['meta']['success'] = false;
                $results['meta']['msg']     = $msg;
            } else {
                if ( !empty( $avatar ) ) {
                    $file_name      = $avatar->getClientOriginalName();
                    $file_extension = $avatar->getClientOriginalExtension();
                    $file_real_path = $avatar->getRealPath();
                    $file_mime      = $avatar->getMimeType();
                    $file_size      = $avatar->getClientSize();

                    if ( $file_size > UPLOAD_MAX_FILE_SIZE ) {
                        // file size too large
                        $results['meta']['code']    = '0006';
                        $results['meta']['success'] = false;
                        $results['meta']['msg']     = Lang::get('message.web.error.0006');
                        
                        return response()->json($results);
                    }

                    // image mime types start with "image/"
                    if( in_array($file_mime, $this->ALLOWED_MIME_TYPES) ) {
                        // this is an image
                        // move file to public path
                        $full_path_file = 'upload/' . $file_name;
                        $avatar->move( public_path() . '/upload', $file_name );

                        $data['avatar_path'] = $full_path_file;
                    } else {
                        // this is not an image
                        $results['meta']['code']    = '0004';
                        $results['meta']['success'] = false;
                        $results['meta']['msg']     = Lang::get('message.web.error.0004');
                        
                        return response()->json($results);
                    }
                }

                $updated_record = $ModelUser->update_db( $data, $where );

                if ( $updated_record['meta']['success'] ) {
                    // update successfull
                    $results['response']        = $updated_record['response'];
                    $results['meta']['msg']     = Lang::get('message.web.success.0002');
                } else {
                    $results['meta']['code']    = '0002';
                    $results['meta']['success'] = false;
                    $results['meta']['msg']     = Lang::get('message.web.error.0002');
                }
            }
        } else {
            // record not found => return message
            $results['meta']['code']    = '0001';
            $results['meta']['success'] = false;
            $results['meta']['msg']     = Lang::get('message.web.error.0001');
        }

        return response()->json($results);
    }

    public function deleteData()
    {
        $ModelUser      = new ModelUser();
        $Response       = new Response();
        $results        = $Response->response(200,'','',true);
        $id             = Input::get('id');
        $deleted_time   = strtotime( \Carbon\Carbon::now()->toDateTimeString() );
        $where          = [
            [
                'fields'    => 'id',
                'operator'  => '=',
                'value'     => $id
            ]
        ];
        $check_id       = $ModelUser->checkExistsData( $where );

        if ( COUNT( $check_id['response'] ) > 0 ) {
            // record exists => delete data
            $data = [
                'deleted_time' => $deleted_time,
            ];

            $deleted_record = $ModelUser->update_db( $data, $where );

            if ( $deleted_record['meta']['success'] ) {
                // delete successfull
                $results['response']        = [ 'id' => $id ];
                $results['meta']['msg']     = Lang::get('message.web.success.0003');
            } else {
                // something happend to server, update fail
                $results['meta']['code']    = '0002';
                $results['meta']['success'] = false;
                $results['meta']['msg']     = Lang::get('message.web.error.0002');
            }
        } else {
            // record not found => return message
            $results['meta']['code']    = '0001';
            $results['meta']['success'] = false;
            $results['meta']['msg']     = Lang::get('message.web.error.0001');
        }

        return response()->json($results);
    }

    public function sortPagination()
    {
        $ModelUser      = new ModelUser();
        $Response       = new Response();
        $results        = $Response->response(200,'','',true);
        $selected_page  = Input::get('selected_page');
        $sort_by        = Input::get('sort_by');
        $sort_type      = Input::get('sort_type');
        $limit          = LIMIT_RECORD_PER_PAGE;
        $offset         = null;
        $selectType     = Config::get('system.type.query.paginate');
        $fields         = null;
        $column         = [];

        $where      = [
            [
                'fields'    => 'deleted_time',
                'operator'  => 'null',
            ]
        ];

        if ( !isset($sort_by) || !isset($sort_type) ) {
            $sort_by    = 'created_time';
            $sort_type  = 'DESC';
        }

        $order      = [
            [
                'fields'    => $sort_by,
                'operator'  => $sort_type
            ]
        ];

        $results = $ModelUser->select( $where, $limit, $offset, $selectType, $order, $fields, $column, $selected_page, $selected_page );

        return response()->json($results);
    }

}