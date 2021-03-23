<?php

namespace App\Http\Controllers\Api\v1\Handbook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Handbook\{Handbook,Subtitle};
use Validator;
use Exception;
use DB;

class HandbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $handbooks = Handbook::with('user')->first();
            return response()->json([
                'message' => 'Lista de manuales',
                'data' => $handbooks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'HandbookController.index.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();//transaction
            $validator_handbook = Validator::make($request->all(), [
                'name'        => 'required|string|min:5|max:255',
                'description' => 'required|string|min:5|max:600'
            ]);
            if ($validator_handbook->fails()) {
               return response()->json(['error' => $validator_handbook->errors()], 422);
            }
            $request['user_id'] = auth()->id();
            $handbook = Handbook::create($request->all());
            if(count($request->name1) == count($request->description1)){
                $validator_subtitle = Validator::make($request->all(), [
                    'name1.*'        => 'string|min:5|max:255',
                    'description1.*' => 'string|min:5|max:600'
                ]);
                if ($validator_subtitle->fails()) {
                   return response()->json(['error' => $validator_subtitle->errors()], 422);
                }
                for($k = 0; $k < count($request->name1); $k++){
                    if(isset($request->name1[$k]) ? $request->name1[$k] : ''){
                        $subtitles = Subtitle::create([
                            'name'        => $request->name1[$k],
                            'description' => $request->description1[$k],
                            'handbook_id' => $handbook->id
                        ]);
                        if(isset($request->image[$k]) ? $request->image[$k] : ''){
                            $fileName = uniqid().$request->image[$k]->getClientOriginalName();
                            $request['url'] = $request->image[$k]->storeAs('img_system_wiki', $fileName,'public');
                            $subtitles->image()->create($request->all());
                        }
                    }
                }
            } 
            $handbooks = Handbook::whereId($handbook->id)->with('subtitles.image','user')->first();
            DB::commit();
            return response()->json([
                'message' => 'Manual creado con éxito',
                'data' => $handbooks
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'HandbookController.store.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $handbook = Handbook::with('subtitles.image','user')->findOrFail($id);
            return response()->json([
                'message' => 'Detalles del manual',
                'data' => $handbook
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'HandbookController.show.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator_handbook = Validator::make($request->all(), [
                'name'        => 'required|string|min:5|max:255',
                'description' => 'required|string|min:5|max:600'
            ]);
            if ($validator_handbook->fails()) {
                return response()->json(['error' => $validator_handbook->errors()], 422);
            }
            $handbook = Handbook::findOrFail($id);
            $handbook->update($request->all());
            $handbook->fresh();
            return response()->json([
                'message' => 'Título y descripción actualizados con éxito',
                'data' => $handbook
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'HandbookController.update.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $handbooks = Handbook::with('subtitles','subtitles.image')->findOrFail($id);
            foreach ($handbooks['subtitles'] as $subtitle) {
                $subtitleImg = Subtitle::findOrFail($subtitle->id);
                if (isset($subtitleImg->image->url)) {
                    unlink($subtitleImg->image->url);
                    $subtitleImg->delete();
                }
            }
            $handbooks->delete();
            return response()->json([
                'message' => 'Manual eliminado con éxito',
                'data' => $handbooks
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'HandbookController.destroy.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }
}
