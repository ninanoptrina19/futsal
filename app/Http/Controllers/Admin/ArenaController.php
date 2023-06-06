<?php

namespace App\Http\Controllers\Admin;

use App\Models\Arena;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArenaRequest;
use App\Http\Controllers\Traits\MediaUploadingTrait;

class ArenaController extends Controller
{
    use MediaUploadingTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arenas = Arena::all();

        return view('admin.arenas.index', compact('arenas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.arenas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $data = $request->all();
        
        
        if ($image = $request->file('image')) {
            $path = 'public/posts';
            $namaGambar = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($path, $namaGambar);
            $data['image'] = $namaGambar;
        }
        
        $data = Arena::create($data);

    return redirect()->route('admin.arenas.index')->with([
        'message' => 'Successfully created!',
        'alert-type' => 'success'
    ]);
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Arena $arena)
    {
        return view('admin.arenas.show', compact('arena'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Arena $arena)
    {
        return view('admin.arenas.edit', compact('arena'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $arena = Arena::findOrFail($id);

    if ($image = $request->file('image')) {
        $path = 'public/posts';

        // Menghapus gambar lama jika ada
        if ($arena->image) {
            Storage::delete($path . '/' . $arena->image);
        }

        $namaGambar = date('YmdHis') . "." . $image->getClientOriginalExtension();
        $image->move($path, $namaGambar);
        $data['image'] = $namaGambar;
    }

    $arena->update($data);

        return redirect()->route('admin.arenas.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Arena $arena)
    {
        $arena->delete();

        return redirect()->route('admin.arenas.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        Arena::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
