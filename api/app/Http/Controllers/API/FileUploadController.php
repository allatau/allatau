<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\File;
use Validator;
use Illuminate\Http\Request;
class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        $host = $request->getSchemeAndHttpHost();
        $validator = Validator::make($request->all(),[
            'file' => 'required',
        ]);

        if($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401);
        }


        if ($file = $request->file('file')) {
            $file_entity = new File();
            $id = Str::uuid()->toString();
            $name = $file->getClientOriginalName();
            $path = $file->storeAs('public/cases', $id . '_' . $name);


            //store your file into directory and db
            $file_entity->id = $id;
            $file_entity->name = $name;
            $file_entity->path= $path;
            $file_entity->save();

            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "file" => $file,
                "name" => $name,
                "path" => $host .'/'. $path,
                "id" => $id,
            ]);

        }


    }
}
