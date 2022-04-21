<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateEditRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Exists;

class UserController extends Controller
{
    
    public function index(Request $request)
    {
        

        $data = User::orderBy('id','DESC')->with('images')->paginate(5);
        // dd($data);
        return view('users.index',compact('data'))
        ->with('i', ($request->input('page', 1) - 1) * 5)
        ->with('j', ($request->input('page', 1) - 1) * 5)
        ->with('k', ($request->input('page', 1) - 1) * 5);
    }
    
    
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles'));
    }
    
    

   


    public function store(StoreUserRequest $request)
    {
        
        $input = $request->all();

        $input['password'] = Hash::make($input['password']);
       
        $images = [];

        if ($request->hasfile('profileAvatar')) {
            foreach($request->file('profileAvatar') as $imgArr) {

                $destinationPath = 'public/img';
                $imageName = time() . $imgArr->getClientOriginalName();
            
                $path = $imgArr->storeAs($destinationPath, $imageName);
                $images[]['name'] =  $imageName;
            }           
        }

        $user = User::create($input);
        $user->assignRole($request->input('roles'));
       
        $user->images()->createMany(
            $images
         );
       
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }
    
   
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    
   
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }
    
    
    public function update(UpdateEditRequest $request, $id)
    {
        $input = $request->all();   

        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        
        $user->update($input);
        // DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        $images = [];

        if ($request->hasfile('profileAvatar')) {
            foreach($request->file('profileAvatar') as $imgArr) {

                $destinationPath = 'public/img';
                $imageName = time() . $imgArr->getClientOriginalName();
            
                $path = $imgArr->storeAs($destinationPath, $imageName);
               
                $images[]['name'] =  $imageName;
            
            }           
        }

        $user->images()->createMany(
            $images
        );
     
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    
    //delete
    public function destroy($id)
    {
        $user = User::find($id);
        $images = $user->images()->get();

        foreach($images as $img) {
            $path = public_path().'/storage/img/'. $img['name'];
            unlink($path);
        }
    
        $user->delete();
        
        return response()->json(['status' => 'deleted']);            
    }

}