<?php

namespace sisVentas\Http\Controllers;

use Illuminate\Http\Request;
use sisVentas\Http\Requests;
use sisVentas\Categoria;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\ArticuloFormRequest;
use sisVentas\Articulo;
use DB;


class ArticuloController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        if ($request)
        {
            $query=trim($request->get('searchText'));//filtro de busqueda
            $articulos=DB::table('articulo as a')
            ->join('categoria as c','a.idcategoria','=','c.idcategoria')//unir la tabla articulo con alias a con la tabla categoria con el alias c
            ->select('a.idarticulo','a.nombre','a.codigo','a.stock','c.nombre as categoria','a.descripcion','a.imagen','a.estado')//seleciono los campos que quiero

            ->where('a.nombre','LIKE','%'.$query.'%')//busca por el nombre del articulo condicion
            ->orwhere('a.codigo','LIKE','%'.$query.'%')//busca por el codigo del articulo condicion
            ->orderBy('a.idarticulo','desc')//ordenar de forma decenciente
            ->paginate(7);
            return view('almacen.articulo.index',["articulos"=>$articulos,"searchText"=>$query]); //retorna la vista index con parametros todas categorias
        }
    }
    public function create()
    {
        //selecciono todos mis registros de la tabla categoria pero que estan activas
        $categorias=DB::table('categoria')
        ->where('condicion','=','1')->get();//selecciono todos mis registros de la tabla categoria pero que estan activas
        return view("almacen.articulo.create",["categorias"=>$categorias]);
    }
    //almacena el objeto del modelo categoria en nustra tabla articulo de la base de datos
    public function store (ArticuloFormRequest $request)//valida el objeto CategoriaFormRequest
    {
           
        
        $articulo=new Articulo;
        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        $articulo->estado='Activo';

        //Cargar y validación para cargar la imagen
        if(Input::hasFile('imagen')){
            $file=Input::file('imagen');
            $file->move(public_path().'/imagenes/articulos/',$file->getClientOriginalName());
            $articulo->imagen=$file->getClientOriginalName();
           
         }
         $articulo->save();
         return Redirect::to('almacen/articulo');//redirecciona al listado de as categorias

    }
    public function show($id)
    {
        return view("almacen.articulo.show",["articulo"=>Articulo::findOrFail($id)]);
    }

    public function edit($id)
    {
        $articulo=Articulo::findOrFail($id);
        $categorias=DB::table('categoria')->where('condicion','=','1')->get();
        return view("almacen.articulo.edit",["articulo"=>$articulo,"categorias"=>$categorias]);
    }
    public function update(ArticuloFormRequest $request,$id)
    {
        $articulo=Articulo::findOrFail($id);

      
        $articulo->idcategoria=$request->get('idcategoria');
        $articulo->codigo=$request->get('codigo');
        $articulo->nombre=$request->get('nombre');
        $articulo->stock=$request->get('stock');
        $articulo->descripcion=$request->get('descripcion');
        

           if(Input::hasFile('imagen')){
              $file=Input::file('imagen');
              $file->move(public_path().'/imagenes/articulos',$file->getClientOriginalName());
              $articulo->imagen=$file->getClientOriginalName();
           } 
        $articulo->update();
        return Redirect::to('almacen/articulo');
    }
    public function destroy($id)
    {
        $articulo=Articulo::findOrFail($id);
        $articulo->estado='Inactivo';
        $articulo->delete();
        return Redirect::to('almacen/articulo');
    }

}
