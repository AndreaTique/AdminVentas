<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;
use sisVentas\Http\Requests;
use sisVentas\Categoria;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Http\Requests\CategoriaFormRequest;
use DB;


class CategoriaController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));//filtro de busqueda
            $categorias=DB::table('categoria')->where('nombre','LIKE','%'.$query.'%')//condicion 1
            ->where ('condicion','=','1')//condicion 2
            ->orderBy('idcategoria','desc')//ordenar de forma decenciente
            ->paginate(7);//registros de paginacion
            return view('almacen.categoria.index',["categorias"=>$categorias,"searchText"=>$query]);//retorna la vista index con parametros todas categorias
        }
    }
    public function create()
    {
        return view("almacen.categoria.create");
    }
    //amacena el objeto del modelo categoria en nustra tabla categoria de la base de datos
    public function store (CategoriaFormRequest $request)//valida el objeto CategoriaFormRequest
    {
        $categoria=new Categoria;
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->condicion='1';
        $categoria->save();
        return Redirect::to('almacen/categoria');//redirecciona al listado de as categorias

    }
    public function show($id)
    {
        return view("almacen.categoria.show",["categoria"=>Categoria::findOrFail($id)]);
    }
    public function edit($id)
    {
        return view("almacen.categoria.edit",["categoria"=>Categoria::findOrFail($id)]);
    }
    public function update(CategoriaFormRequest $request,$id)
    {
        $categoria=Categoria::findOrFail($id);
        $categoria->nombre=$request->get('nombre');
        $categoria->descripcion=$request->get('descripcion');
        $categoria->update();
        return Redirect::to('almacen/categoria');
    }
    public function destroy($id)
    {
        $categoria=Categoria::findOrFail($id);
        $categoria->condicion='0';
        $categoria->delete();
        return Redirect::to('almacen/categoria');
    }





}
