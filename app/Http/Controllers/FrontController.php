<?php

namespace App\Http\Controllers;
use App\Models\favoris;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class FrontController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function films()
    {
        
        return view('films');
    }
    public function films_favoris($id)
    {
       
        if($id==0)
        $favoris=favoris::where('user_id',Auth::user()->id)->where('films_id','!=',null)->get();
        else
        $favoris=favoris::where('user_id',Auth::user()->id)->where('serie_id','!=',null)->get();
       
        return view('films_favoris',['favoris'=>$favoris,'categorie'=>$id]);
    }
    public function films_top($id)
    {
        return view('films_top',['categorie'=>$id]);
    }
    public function series()
    {
        
        return view('series');
    }
    public function favoris(Request $request)
    {
       if($request->input('type')=='add'){
        $fav=new favoris();
        $fav->user_id=Auth::user()->id;
        if($request->input('films_id')!=null)
        if(favoris::where('films_id',$request->input('films_id'))->first())
        return response()->json(['success'=>'Film existe déja']);
        if($request->input('serie_id')!=null)
        if(favoris::where('serie_id',$request->input('serie_id'))->first())
        return response()->json(['success'=>'Serie existe déja']);
        $fav->films_id=$request->input('films_id');
        $fav->serie_id=$request->input('serie_id');
        $fav->save();
        return response()->json(['success'=>'Data is successfully added']);
       }
       else{
        if($request->input('films_id')!=null){
            favoris::where('films_id',$request->input('films_id'))->delete();
            return response()->json(['success'=>'Film supprimer']);
        }
        if($request->input('serie_id')!=null){
            favoris::where('serie_id',$request->input('serie_id'))->delete();
            return response()->json(['success'=>'Serie supprimer']);
        }
       }
    }
}
