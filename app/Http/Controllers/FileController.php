<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class FileController extends Controller
{
    public function file(Request $request) {
        $post = new Post;
        if($request->hasFile('image')) {
         //  dd('image');   // visualizza la scritta image
           $completeFileName = $request->file('image')->getClientOriginalName();
        //   dd($completeFileName);   //  visualizza il nome completo del file (pallinorosso.jpg)
           $filenameOnly = pathinfo($completeFileName, PATHINFO_FILENAME);
          //  dd($filenameOnly);  // visualizza il nome del file senza estensione (pallinoRosso)
          $extenshion = $request->file('image')->getClientOriginalExtension();
         // dd($extenshion);  // visualizza estensione del file (.jpg)
            $compPic = str_replace(' ', '_', $filenameOnly).'-'.rand() . '_'.time(). '.'.$extenshion;
           // dd($compPic);  // rinomina il file mettendo dopo il nome un numero random e il time + estensione (pallinorosso-rnad_time.jpg)
            $path = $request->file('image')->storeAs('public/posts', $compPic);
          //  dd($path);  // visualizzo la path dove ho salvato il file
          $post->image = $compPic;
    }
    if($post->save()) {
        return ['status' => true, 'message' => 'Upload concluso con successo', 'file' => $compPic];
    } else {
        return ['status' => false, 'message' => 'problemi in salvataggio file'];
    }
}

}