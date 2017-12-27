<?php

namespace App\Http\Models;

use DB;
use App;
use Cache;
use Config;
use App\Http\Response\Response;
use Illuminate\Database\Eloquent\Model;
/**
*
*/
class User extends Model
{

	protected $table = 'users';

	public function select ( $where = array(), $limit = null, $offset = null, $selectType = null, $order = null, $fields = null, $column = [])
	{
		
		$Response = new Response();
		$results = $Response->response(200,'','',true);

		try {
			$query = DB::table( $this->table );

			if( !empty( $column ) ) {

				$query = $query->select( $column );
			} else {
				$query = $query->select();
			}

			foreach ($where as $key => $value) {

				switch ($value['operator']) {
					case 'in':
						$query = $query->whereIn($value['fields'], $value['value']);
						break;
					case 'null':
						$query = $query->whereNull($value['fields']);
						break;
					case 'raw':
						$query = $query->whereRaw($value['sql']);
						break;
					case 'or':
						$query = $query->whereOr($value['fields'], $value['sub_operator'], $value['value']);
						break;
					default:
						$query = $query->where($value['fields'], $value['operator'], $value['value']);
						break;
				}
			}

			if(!is_null($limit)  && !is_null($offset) && $selectType != Config::get('system.type.query.paginate')){
				$query = $query->take($limit)->skip($offset);
			}

			if($order !== null) {

				foreach ($order as $key => $value) {

					if($value['fields'] != ''){
						$query = $query->orderBy($value['fields'],$value['operator']);
					}
				}
			}

			// DB::enableQueryLog();

			switch ($selectType) {
				case Config::get('system.type.query.count'):
				// DB::enableQueryLog();
					$query = $query->count();
					break;
				case Config::get('system.type.query.max'):
					$query = $query->max($fields);
					break;
				case Config::get('system.type.query.min'):
					$query = $query->min($fields);
					break;
				case Config::get('system.type.query.paginate'):

					$query = $query->paginate($limit);
					break;
				default :
					$query = $query->get();
					break;
			}
			// $queries = DB::getQueryLog();
			// 	$last_query = end($queries);
			// 	print_r($last_query);
				// exit;
			$results['response'] = $query;

		} catch (PDOException $e) {

			$results['meta']['success'] = false;
			$results['meta']['code'] 	= 401;
			$results['meta']['msg'] 	= $e->getMessage();
		}

		return $results;
	}

	public function insert ( $data )
	{
		
		$Response = new Response();
		$results = $Response->response(200,'','',true);

		try {
			$query = DB::table($this->table);

			$query->insert($data);

		} catch (PDOException $e) {

			$results[ META ][ SUCCESS ] = false;
			$results[ META ][ MSG ] = $e->getMessage();
		}
		return $results;
	}

	public function insertGetId ( $data, $where = array())
	{
		
		$Response = new Response();
		$results = $Response->response(200,'','',true);

		try {

			$query = DB::table( $this->table );

			foreach ($where as $key => $value) {

				switch ( $value['operator'] ) {
					case 'in':
						$query = $query->whereIn($value['fields'], $value['value']);
						break;
					case 'null':
						$query = $query->whereNull($value['fields']);
						break;
					default:
						$query = $query->where($value['fields'], $value['operator'], $value['value']);
						break;
				}
			}

			$results[ RESPONSE ] = $query->insertGetId($data);

		} catch (PDOException $e) {

			$results[ META ][ SUCCESS ] = false;
			$results[ META ][ MSG ] = $e->getMessage();
		}

		return $results;
	}

	public function update_db ( $data, $where)
	{
		
		$Response = new Response();
		$results = $Response->response(200,'','',true);

		try {

			$query = DB::table($this->table);

			foreach ( $where as $key => $value ) {

				switch ($value['operator']) {
					case 'in':
						$query = $query->whereIn($value['fields'], $value['value']);
						break;
					case 'null':
						$query = $query->whereNull($value['fields']);
						break;
					default:
						$query = $query->where($value['fields'], $value['operator'], $value['value']);
						break;
				}
			}

			// DB::enableQueryLog();
			$querry = $query->update($data);
			// $queries = DB::getQueryLog();
			// $last_query = end($queries);
			// print_r($last_query);
			// exit;
		} catch (PDOException $e) {

			$results[ META ][ SUCCESS ] = false;
			$results[ META ][ MSG ] = $e->getMessage();
		}

		return $results;
	}

	public function checkExistsData( $field, $value )
	{

		$Response   = new Response();
        $results    = $Response->response(200,'','',true);

        try {

            $query = DB::table( $this->table . ' as ' . $this->table )
                                        ->where( $field , '=', $value )
                                        ->whereNull( $this->table . '.deleted_time' )
                                        ->first();

            $results['response'] = $query;

        } catch (PDOException $e) {

            $results['meta']['success'] = false;
            $results['meta']['msg']     = $e->getMessage();
        }

        return $results;
	}
}