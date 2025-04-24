<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourrierRequest;
use App\Models\Courrier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CourrierController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()->of(Courrier::select(['id', 'name', 'courrier_email', 'courrier_phone']))
                ->make(true);
        }

        return view('courriers.index');
    }

    public function store(CourrierRequest $request)
    {
        Courrier::create($request->validated());

        return response()->json(['message' => 'Courrier created successfully.']);
    }

    public function update(CourrierRequest $request, $id)
    {
        $courrier = Courrier::findOrFail($id);
        $courrier->update($request->validated());

        return response()->json(['message' => 'Courrier updated successfully.']);
    }

    public function destroy($id)
    {
        $courrier = Courrier::findOrFail($id);
        $courrier->delete();

        return response()->json(['message' => 'Courrier deleted successfully.']);
    }
}
