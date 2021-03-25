<?php

namespace App\Http\Controllers\Api\v1\Handbook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Handbook\Image;
use Validator;
use Exception;

class ImageController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'subtitle_id' => 'required|integer|exists:subtitles,id',
                'image' => 'required'
            ]);
            if ($validator->fails()) {
               return response()->json(['error' => $validator->errors()], 422);
            }
            if($request->hasFile('image')){
                $fileName = uniqid().$request->image->getClientOriginalName();
                $request['url'] = $request->image->storeAs('img_system_wiki', $fileName,'public');
                $image = Image::create($request->all());
                $imageSub = Image::whereId($image->id)->with('subtitle')->first();
            }
            return response()->json([
                'message' => 'Imagen creada con éxito',
                'data' => $imageSub
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'ImageController.store.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'subtitle_id' => 'required|integer|exists:subtitles,id',
                'image' => 'required'
            ]);
            if ($validator->fails()) {
               return response()->json(['error' => $validator->errors()], 422);
            }
            if($request->hasFile('image')){
                $fileName = uniqid().$request->image->getClientOriginalName();
                $request['url'] = $request->image->storeAs('img_system_wiki', $fileName,'public');
                $image = Image::findOrFail($id);
                $url = $image->url;
                $image->update($request->all());
                if(isset($url)){
                    unlink($url);
                }
                $imageSub = Image::whereId($image->id)->with('subtitle')->first();
            }
            return response()->json([
                'message' => 'Imagen actualizada con éxito',
                'data' => $imageSub
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'ImageController.update.failed',
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
            $image = Image::findOrFail($id);
            $url = $image->url;
            if(isset($url) && $url != null){
                $image->delete();
                unlink($url);
            }
            return response()->json([
                'message' => 'Imagen eliminada con éxito',
                'data' => $image
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'ImageController.destroy.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }
}
