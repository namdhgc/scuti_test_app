<?php
namespace Tests\Feature;

use App\Http\Controllers\UserController as ControllerUser;
use App\Http\Models\User as ModelUser;
use App\Http\Response\Response;
use Image;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

final class UserControllerTest extends TestCase
{
	use DatabaseMigrations;

	public function testAddUserSuccess()
	{
		$Response 		= new Response();
    	$base_response	= $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
		$request_array 	= [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $response = $this->call('POST', 'user/add', $request_array);

        // dd($response->getOriginalContent());
        $this->assertDatabaseHas('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $base_response );
	}

	public function testAddUserFailWithConditionNameTooLong()
	{
		$Response 		= new Response();
    	$base_response	= $Response->response( '0005', "name: The name may not be greater than 100 characters.<br>", "", false );
		$request_array = [
            'name' 		=> bin2hex(random_bytes(51)),
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $response = $this->call('POST', 'user/add', $request_array);

        $this->assertDatabaseMissing('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $base_response );
	}

	public function testAddUserFailWithConditionAddressTooLong()
	{
		$Response 		= new Response();
    	$base_response	= $Response->response( '0005', "address: The address may not be greater than 300 characters.<br>", "", false );
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> bin2hex(random_bytes(151)),
            'age' 		=> 22,
        ];
        $response = $this->call('POST', 'user/add', $request_array);

        $this->assertDatabaseMissing('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $base_response );
	}

	public function testAddUserFailWithConditionAgeWrongFormat()
	{
		$Response 		= new Response();
    	$base_response	= $Response->response( '0005', "age: The age must be a number.<br>", "", false );
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test dia chi',
            'age' 		=> 'abc',
        ];
        $response = $this->call('POST', 'user/add', $request_array);

        $this->assertDatabaseMissing('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $base_response );
	}

	public function testUpdateUserSuccess()
	{
        $Response           = new Response();
    	$base_response	    = $Response->response( '200', Lang::get('message.web.success.0002'), "", true );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
		$update_request_array = [
            'id' 		=> 1,
            'name' 		=> 'def',
            'address' 	=> 'Update test Dia Chi',
            'age' 		=> 23,
        ];
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );

        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseHas('users',
            [
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $update_response->assertJson( $base_response );
	}

	public function testUpdateUserFailConditionIdNotExists()
	{
        $Response           = new Response();
        $base_response      = $Response->response( '0001', Lang::get('message.web.error.0001'), "", false );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $update_request_array = [
            'id' 		=> 2,
            'name' 		=> 'def',
            'address' 	=> 'Update test Dia Chi',
            'age' 		=> 23,
        ];

        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'name'      => $request_array['name'],
                'age'       => $request_array['age'],
                'address'   => $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseMissing('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $update_response->assertJson( $base_response );
	}

	public function testUpdateUserFailConditionNameTooLong()
	{
        $Response           = new Response();
    	$base_response	    = $Response->response( '0005', "name: The name may not be greater than 100 characters.<br>", "", false );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $update_request_array = [
            'id' 		=> 1,
            'name' 		=> bin2hex(random_bytes(51)),
            'address' 	=> 'Update test Dia Chi',
            'age' 		=> 23,
        ];
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'id'        => $update_request_array['id'],
                'name'      => $request_array['name'],
                'age'       => $request_array['age'],
                'address'   => $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseMissing('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $update_response->assertJson( $base_response );
	}

	public function testUpdateUserFailConditionAddressTooLong()
	{
        $Response           = new Response();
        $base_response	    = $Response->response( '0005', "address: The address may not be greater than 300 characters.<br>", "", false );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $update_request_array = [
            'id' 		=> 1,
            'name' 		=> 'def',
            'address' 	=> bin2hex(random_bytes(151)),
            'age' 		=> 23,
        ];
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'id'        => $update_request_array['id'],
                'name'      => $request_array['name'],
                'age'       => $request_array['age'],
                'address'   => $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseMissing('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $update_response->assertJson( $base_response );
	}

	public function testUpdateUserFailConditionAgeWrongFormat()
	{
        $Response           = new Response();
        $base_response	    = $Response->response( '0005', "age: The age must be a number.<br>", "", false );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $update_request_array = [
            'id' 		=> 1,
            'name' 		=> 'def',
            'address' 	=> 'Update test Dia Chi',
            'age' 		=> 'abc',
        ];
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'id'        => $update_request_array['id'],
                'name'      => $request_array['name'],
                'age'       => $request_array['age'],
                'address'   => $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseMissing('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $update_response->assertJson( $base_response );
	}

	public function testDeleteUserSuccess()
	{
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $delete_request_array = [
            'id' 		=> 1,
        ];
        $Response 			= new Response();
    	$base_response		= $Response->response( '200', Lang::get('message.web.success.0003'), $delete_request_array, true );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $delete_response 	= $this->call('POST', 'user/delete', $delete_request_array);
        $this->assertDatabaseMissing('users',
            [
                'id' 			=> $delete_request_array['id'],
                'name' 			=> $request_array['name'],
                'age' 			=> $request_array['age'],
                'address' 		=> $request_array['address'],
                'deleted_time' 	=> null
            ]);
        $delete_response->assertJson( $base_response );
	}

	public function testDeleteUserFailConditionIdNotExists()
	{
		$request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $delete_request_array = [
            'id' 		=> 2,
        ];
        $Response 			= new Response();
    	$base_response		= $Response->response( '0001', Lang::get('message.web.error.0001'), "", false );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'id' 		=> 1,
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $delete_response 	= $this->call('POST', 'user/delete', $delete_request_array);
        $this->assertDatabaseHas('users',
            [
                'id' 			=> 1,
                'name' 			=> $request_array['name'],
                'age' 			=> $request_array['age'],
                'address' 		=> $request_array['address'],
                'deleted_time' 	=> null
            ]);
        $delete_response->assertJson( $base_response );
	}

	public function testAddUserSuccessWithImage()
    {
        $Response           = new Response();
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
        $stub               = __DIR__.'/stubs/avatar.png';
        $name               = str_random(8).'.png';
        $path               = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file               = new UploadedFile($path, $name, 'image/png', filesize($path), null, true);

        $request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
            'avatar' 	=> $file,
        ];
        $response = $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $uploaded = public_path() . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $file->getClientOriginalName();
        unlink($uploaded);
    }

	public function testAddUserFailWithImageConditionNotAnImage()
    {
        $Response           = new Response();
    	$base_response	    = $Response->response( '0004', Lang::get('message.web.error.0004'), "", false );
        $stub               = __DIR__.'/stubs/music.mp3';
        $name               = str_random(8).'.mp3';
        $path               = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file               = new UploadedFile($path, $name, 'audio/mpeg', filesize($path), null, true);

        $request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
            'avatar' 	=> $file,
        ];
        $response = $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseMissing('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $base_response );
    }

	public function testAddUserFailWithImageConditionIsImageButWrongType()
    {
		$Response 		= new Response();
    	$base_response	= $Response->response( '0004', Lang::get('message.web.error.0004'), "", false );
        $stub           = __DIR__.'/stubs/sample_dwf.dwf';
        $name           = str_random(8).'.dwf';
        $path           = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file           = new UploadedFile($path, $name, 'audio/mpeg', filesize($path), null, true);

        $request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
            'avatar' 	=> $file,
        ];
        $response = $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseMissing('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $base_response );
    }

	public function testAddUserFailWithImageConditionFileTooLarge()
    {
		$Response 		= new Response();
    	$base_response	= $Response->response( '0006', Lang::get('message.web.error.0006'), "", false );
        $stub = __DIR__.'/stubs/file_too_large.mp4';
        $name = str_random(8).'.mp4';
        $path = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file = new UploadedFile($path, $name, 'audio/mpeg', filesize($path), null, true);

        $request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
            'avatar' 	=> $file,
        ];
        $response = $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseMissing('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $base_response );
    }

	public function testUpdateUserSuccessWithImage()
    {
        $Response           = new Response();
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
        $stub               = __DIR__.'/stubs/avatar.png';
        $name               = str_random(8).'.png';
        $path               = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file               = new UploadedFile($path, $name, 'image/png', filesize($path), null, true);

        $request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
        $update_request_array = [
            'id' 		=> 1,
            'name' 		=> 'def',
            'address' 	=> 'Update test Dia Chi',
            'age' 		=> 23,
            'avatar' 	=> $file,
        ];
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'name' 		=> $request_array['name'],
                'age' 		=> $request_array['age'],
                'address' 	=> $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseHas('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $uploaded = public_path() . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $file->getClientOriginalName();
        unlink($uploaded);
    }

	public function testUpdateUserFailWithImageConditionNotAnImage()
    {
        $Response           = new Response();
    	$base_response	    = $Response->response( '0004', Lang::get('message.web.error.0004'), "", false );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
        $stub               = __DIR__.'/stubs/music.mp3';
        $name               = str_random(8).'.mp3';
        $path               = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file               = new UploadedFile($path, $name, 'audio/mpeg', filesize($path), null, true);

        $request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
       	$update_request_array = [
            'id' 		=> 1,
            'name' 		=> 'def',
            'address' 	=> 'Update test Dia Chi',
            'age' 		=> 23,
            'avatar' 	=> $file,
        ];
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'name'      => $request_array['name'],
                'age'       => $request_array['age'],
                'address'   => $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseMissing('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $update_response->assertJson( $base_response );
    }

    public function testUpdateUserFailWithImageConditionIsImageButWrongType()
    {
        $Response           = new Response();
    	$base_response	    = $Response->response( '0004', Lang::get('message.web.error.0004'), "", false );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
        $stub               = __DIR__.'/stubs/sample_dwf.dwf';
        $name               = str_random(8).'.dwf';
        $path               = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file               = new UploadedFile($path, $name, 'audio/mpeg', filesize($path), null, true);

        $request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
       	$update_request_array = [
            'id' 		=> 1,
            'name' 		=> 'def',
            'address' 	=> 'Update test Dia Chi',
            'age' 		=> 23,
            'avatar' 	=> $file,
        ];
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'name'      => $request_array['name'],
                'age'       => $request_array['age'],
                'address'   => $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseMissing('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $update_response->assertJson( $base_response );
    }

    public function testUpdateUserFailWithImageConditionFileTooLarge()
    {
        $Response           = new Response();
    	$base_response	    = $Response->response( '0006', Lang::get('message.web.error.0006'), "", false );
        $add_base_response  = $Response->response( '200', Lang::get('message.web.success.0001'), "", true );
        $stub               = __DIR__.'/stubs/file_too_large.mp4';
        $name               = str_random(8).'.mp4';
        $path               = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file               = new UploadedFile($path, $name, 'audio/mpeg', filesize($path), null, true);

        $request_array = [
            'name' 		=> 'abc',
            'address' 	=> 'Test Dia Chi',
            'age' 		=> 22,
        ];
       	$update_request_array = [
            'id' 		=> 1,
            'name' 		=> 'def',
            'address' 	=> 'Update test Dia Chi',
            'age' 		=> 23,
            'avatar' 	=> $file,
        ];
        $response 			= $this->call('POST', 'user/add', $request_array);
        $this->assertDatabaseHas('users',
            [
                'name'      => $request_array['name'],
                'age'       => $request_array['age'],
                'address'   => $request_array['address'],
            ]);
        $response->assertJson( $add_base_response );
        $update_response 	= $this->call('POST', 'user/edit', $update_request_array);
        $this->assertDatabaseMissing('users',
            [
                'id' 		=> $update_request_array['id'],
                'name' 		=> $update_request_array['name'],
                'age' 		=> $update_request_array['age'],
                'address' 	=> $update_request_array['address'],
            ]);
        $update_response->assertJson( $base_response );
    }

}


