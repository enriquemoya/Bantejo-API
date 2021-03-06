<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Page;
use Validator;


class PagesController extends Controller {

	public function all(Request $data)
    {
        $pages = Page::get();
        if(!$pages->isEmpty())
        {
            return response()->json(['error'=>false,'message'=>'ok','pages'=>$pages]);
        }
        return response()->json(['error'=>true,'message'=>'no hay paginas registradas.','pages'=>null]);
    } 
    public function show($id)
    {
        $page = Page::where('id',$id)->get();
        if(!$page->isEmpty())
        {
            $page = Page::where('id',$id)->first();
            return response()->json(['error'=>false,'message'=>'ok','page'=>$page]);
        }
        return response()->json(['error'=>true,'message'=>'no se encontro pagina.','page'=>null]);
    } 
    public function add(Request $data)
    {       
        $validator = Validator::make($data->all(), [
            'url' => 'required|max:255|unique:pages',
        ]);

        $uniqueValidator = Validator::make($data->all(),[
           'url' => 'unique:pages',
        ]);


        if ($validator->fails()) {
            $failed = $validator->failed();
            //si la pagina ya estaba, pero se le habia hecho soft delete, la restauramos
            if(isset($failed['url']['Unique'])){
                App\Page::where('url',$data['url'])->restore();
                return response()->json(['error'=>false,'message'=>'campo restaurado.']);
            }
            //de otra forma, tiene error
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        else{  
            $page = new App\Page;
            $page->url = $data['url'];
            $page->save();            
            return response()->json(['error'=>false,'message'=>'pagina agregada correctamente.','id'=>$page->id]);
        }
    }
    public function delete($id)
    {
        # code...
        $page = App\Page::where('id', $id)->get();
        if(!$page->isEmpty()){
            try {
                $page = App\Page::where('id', $id)->delete();
                return response()->json(['error'=>false,'message'=>'pagina eliminada correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>true,'message'=>'no se pudo eliminar pagina.','exception'=>$e->getMessage()]);
            }
        }
        return response()->json(['error'=>true,'message'=>'no se encontro pagina.']);
    }
    public function update(Request $request,$id)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'url' => 'max:255|unique:pages',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>true,'message'=>'error al validar campos.','errors'=>$validator->errors()->all()]);
        }
        $page = App\Page::where('id',$id)->get();
        if(!$page->isEmpty()){
            try {
                $page = App\Page::where('id',$id)->find($id);

                if ( $request->has('url') )
                {
                    //actualizamos tanto el url como la fecha en la que se actualizo (la actual)
                    $page->url = $request->get('url');
                    //$page->updated_at = date('Y-m-d H:i:s');
                } 

                $page->save();
            
                return response()->json(['error'=>false,'message'=>'pagina editada correctamente.']);
            } catch (Exception $e) {
                return response()->json(['error'=>false,'message'=>'pagina no se pudo actualizar.','errors'=>$e->getMessage()]);
            }
        }   
        else{
            return response()->json(['error'=>false,'message'=>'no se encontro pagina.']);
        }      
    }
    public function report($id)
    {
        # code...        
        return response()->json(['error'=>false,'message'=>'no se ha definido ningun reporte.']);
    }

}
