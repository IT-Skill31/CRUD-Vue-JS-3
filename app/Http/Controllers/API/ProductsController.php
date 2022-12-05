<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $products = Products::all()->toArray();
        return array_reverse($products);
    }
    
    /**
     * add
     *
     * @param  mixed $request
     * @return void
     */
    public function add(Request $request)
    {
        $request->validate([
            'title'        => 'required',
            'description' => 'required',
            'file'        => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
            'filePdf'     => 'required|mimes:pdf|max:500',
            'url_amazon'  => 'required',
        ]);

        $input = $request->all();
        $imageName = null;
        $pdfFile   = null;
        if ($image = $request->file('file')) {
            $destinationPath = 'images/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $input['image'] = $imageName;
        }
        if ($pdf = $request->file('filePdf')) {
            $destinationPath = 'pdf/';
            $pdfFile = date('YmdHis') . "." . $pdf->getClientOriginalExtension();
            $pdf->move($destinationPath, $pdfFile);
            $input['filePdf'] = $pdfFile;
        }

        Products::create($input);

        return response()->json(['success'=> 'Produit ajouter avec succès!']);

    }
    
    /**
     * edit
     *
     * @param  mixed $id
     * @return void
     */
    public function edit($id)
    {
        $product = Products::find($id);
        return response()->json($product);
    }
    
    /**
     * update
     *
     * @param  mixed $id
     * @param  mixed $request
     * @return void
     */
    public function update($id, Request $request)
    {
        $product = Products::find($id);
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'url_amazon' => 'required',
        ]);

        $input = $request->all();
        $imageName = null;
        if ($image = $request->file('file')) {
            $destinationPath = 'images/';
            $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $imageName);
            $input['image'] = $imageName;
            //unlink('images/'.$product->image);
        }

        if ($pdf = $request->file('filePdf')) {
            $destinationPath = 'pdf/';
            $pdfFile = date('YmdHis') . "." . $pdf->getClientOriginalExtension();
            $pdf->move($destinationPath, $pdfFile);
            $input['filePdf'] = $pdfFile;
            //unlink('images/'.$product->filePdf);
        }
        
        $product->update($input);

        return response()->json(['success'=> 'Produit mis à jour avec succès!']);
    }
    
    /**
     * delete
     *
     * @param  mixed $id
     * @return void
     */
    public function delete($id)
    {
        $product = Products::find($id);
        $product->delete();
        unlink('images/'.$product->image);
        unlink('pdf/'.$product->filePdf);
        return response()->json(['success'=> 'Produit supprimer avec succès!']);

    }
}
