<?php

namespace App\Http\Controllers;
use Input;
use Auth;
use Config;
use Session;
use Cache;
use Lang;
use Image;
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

class UserController extends Controller
{

    function __construct()
    {

    }

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

        $limit      = 1;
        $offset     = null;
        $selectType = Config::get('system.type.query.paginate');
        $fields     = null;
        $column     = [];
        $sort       = null;
        $sort_type  = null;

        $where      = [
            [
                'fields'    => 'deleted_time',
                'operator'  => 'null',
            ]
        ];

        $order      = [
            'fields' => $sort,
            'operator'  => $sort_type
        ];

        $results = $ModelUser->select( $where, $limit, $offset, $selectType, $order, $fields, $column );

        return $results;
    }

    public function insertData()
    {

        $name           = Input::get('name');
        $address        = Input::get('address');
        $age            = Input::get('age');
        $avatar         = Input::get('avatar');
        $created_time   = strtotime( \Carbon\Carbon::now()->toDateTimeString() );

        $tmp_path           = public_path( Config::get('spr.system.uploadMedia.path_tmp_upload'));
        $path               = Config::get('spr.system.uploadMedia.path_image_upload');
        $preview_path       = Config::get('spr.system.uploadMedia.path_tmp_upload');

        $client_original_mime   = $file->getMimeType();
        $filename               = $file[ 'tmp_name' ];

        $file_upload    = $tmp_path . '/' . $filename . '.' . Config::get('spr.type.mimeFile')[$client_original_mime];

        $full_path_file = $path . '/' . $filename . '.' . Config::get('spr.type.mimeFile')[$client_original_mime];
        Image::make($file_upload)->save($full_path_file, 100);

        // $full_path_file_preview = $preview_path . '/' . $filename. '.' . Config::get('spr.type.mimeFile')[$client_original_mime];
        // Image::make($file_upload)->resize(320, null, function ($constraint) {
        //     $constraint->aspectRatio();
        // })->save($full_path_file_preview);

        // insert data into database
        $data = [

            'name'              => $name,
            'address'           => $address,
            'age'               => $age,
            'avatar_path'       => $full_path_file,
            // 'preview_avatar_path'      => $preview_path,
            'mime'              => Config::get('spr.type.mimeFile')[$client_original_mime],
            'created_time'      => $created_time,
        ];

        $id = $ModelMedia->insertData($data);

    }

    public function deleteData()
    {

        $ModelUser      = new ModelUser();
        $Response       = new Response();
        $results        = $Response->response(200,'','',true);
        $id             = Input::get('id');
        $deleted_time   = strtotime( \Carbon\Carbon::now()->toDateTimeString() );
        $field          = 'id';

        $check_id       = $ModelUser->checkExistsData( $field, $id );

        if ( COUNT( $check_id['response'] ) > 0 ) {
            
            // record exists => delete data
            $where = [
                [
                    'fields'    => 'id',
                    'operator'  => '=',
                    'value'     => $id,
                ]
            ];

            $data = [
                'deleted_time' => $deleted_time,
            ];

            $deleted_record = $ModelUser->update_db( $data, $where );
            // $deleted_record['meta']['success'] = true;

            if ( $deleted_record['meta']['success'] ) {
                
                // delete successfull
                $results['response']        = [ 'id' => $id ];
                // $results['response']        = $this->getData()['response'];
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

        return $results;
    }

}