<?php

namespace App\Http\Controllers;


use App\Models\Rol;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index (Request $request){
        $filter = $request->filter;

        if(!empty($request->records_per_page)){
            $request->records_per_page = $request->records_per_parge <= env('PAGINATION_MAX_SIZE')
                                                                    ? $request->records_per_page
                                                                    :   env('PAGINATION_MAX_SIZE');
        } else {
            $request->records_per_page = env('PAGINATION_DEFAULT_SIZE');
        }

        $rols = Rol::where('name', 'LIKE', "%$filter%")
                     ->paginate($request->records_per_page);

        return view('rols.index', ['rols' => $rols,
                                    'data' => $request]);
    }

    /**
     * Show the form for creating a new resource.
     */
 public function create(){
        $modules = Permission::all() -> groupby('module');
        return view('rols/create', [
                    'modules' => $modules,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request){

        Validator::make($request->all(), [

            'name' => 'required|max:64',
            'permissions' => 'required|json',

        ], [
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre no puede tener mas de :max caracteres',
            'permissions.required' => 'Debe elegir al menos un permiso',
            'permissions.json' => 'El campo se encuentra en el formato incorrecto',
        ])->validate();

        try {
            \DB::transaction(function() use($request){
                $rol = new Rol();
                $rol->name = $request->name;
                $rol->save();

                $permissions = json_decode($request->input('permissions'), true);

                foreach ($permissions as $permission) {
                    $rolPermission = new RolPermission();
                    $rolPermission->rol_id = $rol->id;
                    $rolPermission->permission_id = $permission;
                    $rolPermission->save();
                }
            });

            Session::flash('message', ['content' => 'Rol creado con exito', 'type' => 'succes']);
            return redirect()->route('rols.index');
        } catch (Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
 public function edit($id){
        $rol = Rol::find($id);

        if (empty($rol)) {
            Session::flash('message', ['content' => "El rol con el id '$id' no existe.", 'type' => 'error']);
            return redirect()->back();
        };

        $sections = Permission::all();

        $sections = $sections->map(function($item) use($id){
            $item->selected = false;

            $rolPermission = RolPermission::where('permission_id', '=', $item->$id)
                                            ->where('rol_id', '=', $id)
                                            ->first();
        });

        $modules = Permission::all() -> groupby('modules');

        return view('rols/edit', [
                    'rol' => $rol,
                    'modules' => $modules,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, $id) {
        Validator::make($request->all(), [
            'name' => 'required|max:64',
            'permissions' => 'required|json',
        ])->validate();

        try {
            \DB::transaction(function() use($request, $id) {
                $rol = Rol::find($id);
                $rol->name = $request->name;
                $rol->save();

                RolPermission::where('rol_id', '=', $rol->id)->delete();

                $permissions = json_decode($request->permissions);

                foreach ($permissions as $permission) {
                    $rolPermission = new RolPermission();
                    $rolPermission->rol_id = $rol->id;
                    $rolPermission->permission_id = $permission;
                    $rolPermission->save();
                }
            });

            Session::flash('message', ['content' => 'Rol actualizado con éxito', 'type' => 'success']);
            return redirect()->route('rols.index');
        } catch (\Exception $ex) {
            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
 public function delete($id) {

        try {

            $rol = Rol::find($id);

            if (empty($rol)) {

                Session::flash('message', ['content' => "El rol con id: '$id' no existe.", 'type' => 'error']);
                return redirect()->back();
            }

            $rol->delete();

            Session::flash('message', ['content' => 'Rol eliminada con éxito', 'type' => 'success']);
            return redirect()->action([RolsController::class, 'index']);

        } catch(Exception $ex){

            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }
    }
}
