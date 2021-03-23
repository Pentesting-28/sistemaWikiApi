<?php

namespace App\Http\Controllers\Api\v1\Handbook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Handbook\Subtitle;
use Validator;
use Exception;

class SubtitleController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'required|string|min:5|max:255',
                'description' => 'required|string|min:5|max:600',
                'handbook_id' => 'required|integer|exists:subtitles,id'
            ]);
            if ($validator->fails()) {
               return response()->json(['error' => $validator->errors()], 422);
            }
            $subtitle = Subtitle::create($request->all());
            if($request->hasFile('image')){
                $fileName = uniqid().$request->image->getClientOriginalName();
                $request['url'] = $request->image->storeAs('img_system_wiki', $fileName,'public');
                $subtitle->image()->create($request->all());
                $subtitle = Subtitle::whereId($subtitle->id)->with('image')->first();
            }
            return response()->json([
                'message' => 'Subtitulo y contenido creado con éxito',
                'data' => $subtitle
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'SubtitleController.store.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator_subtitle = Validator::make($request->all(), [
                'name'        => 'required|string|min:5|max:255',
                'description' => 'required|string|min:5|max:600'
            ]);
            if ($validator_subtitle->fails()) {
               return response()->json(['error' => $validator_subtitle->errors()], 422);
            }
            $subtitle = Subtitle::findOrFail($id);
            $subtitle->update($request->all());
            $subtitle->fresh();
            return response()->json([
                'message' => 'Subtitulo y contenido actualizado con éxito',
                'data' => $subtitle
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'SubtitleController.update.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }

    public function destroy($id)
    {
        try {
            $subtitle = Subtitle::with('image')->findOrFail($id);
            if(isset($subtitle->image->url)){
                $url = $subtitle->image->url;
            }
            $subtitle->delete();
            if(isset($url)){
                unlink($url);
            }
            return response()->json([
                'message' => 'Subtitulo y contenido eliminado con éxito',
                'data' => $subtitle
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'SubtitleController.destroy.failed',
                'error' => $e->getMessage()
            ], 505);
        }
    }
}
