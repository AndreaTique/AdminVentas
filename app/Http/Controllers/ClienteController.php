<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;

use sisVentas\Http\Requests;
use sisVentas\Persona;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Http\Requests\PersonaFormRequest;
use DB;

class ClienteController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));//filtro de busqueda
            $personas=DB::table('persona')
            ->where('nombre','LIKE','%'.$query.'%')//condicion 1
            ->where ('tipo_persona','=','Cliente')//condicion 2
            ->orwhere('num_documento','LIKE','%'.$query.'%')//condicion 3
            ->where ('tipo_persona','=','Cliente')//condicion 4
            ->orderBy('idpersona','desc')//ordenar de forma decenciente
            ->paginate(7);//registros de paginacion
            return view('ventas.cliente.index',["personas"=>$personas,"searchText"=>$query]);//retorna la vista index con parametros todas categorias
        }
    }
    public function create()
    {
        return view("ventas.cliente.create");
    }
    //amacena el objeto del modelo categoria en nustra tabla categoria de la base de datos
    public function store (PersonaFormRequest $request)//valida el objeto CategoriaFormRequest
    {
        $persona=new Persona;
        $persona->tipo_persona='Cliente';
        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->save();
        return Redirect::to('ventas/cliente');//redirecciona al listado de as categorias

    }
    public function show($id)
    {
        return view("ventas.cliente.show",["persona"=>Persona::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("ventas.cliente.edit",["persona"=>Persona::findOrFail($id)]);
    }
    public function update(PersonaFormRequest $request,$id)
    {
        $persona=Persona::findOrFail($id);
        $persona->nombre=$request->get('nombre');
        $persona->tipo_documento=$request->get('tipo_documento');
        $persona->num_documento=$request->get('num_documento');
        $persona->direccion=$request->get('direccion');
        $persona->telefono=$request->get('telefono');
        $persona->email=$request->get('email');
        $persona->update();
        return Redirect::to('ventas/cliente');
    }
    public function destroy($id)
    {
        $persona=Persona::findOrFail($id);
        $persona->tipo_persona='Inactivo';
        $persona->delete();
        return Redirect::to('ventas/cliente');
    }
}
