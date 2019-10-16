<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Borrowing;
use App\Item;
use App\Student;
use DataTables;
class BorrowingController extends Controller
{
    public function json(){
        $dataBorrowing = Borrowing::select('borrowing.*');
        return DataTables::eloquent($dataBorrowing)->addColumn('aksi',function($data){
            return "<a href='/borrowing/edit/".$data->borrowing_id."' class='badge badge-primary'>Edit</a>";
        })->rawColumns(['aksi'])->toJson();
    }

    public function index(){
        return view('borrowing.index');
    }

    public function create(){
        $dataItem= Item::select('item_id', 'item_name')->where('item_conditions', 'Baik')->where('item_ammount',">",0)->get();
        $dataStudent = Student::select('student_id', 'student_name')->get();
        return view('borrowing.create', compact('dataItem', 'dataStudent'));
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'item_ammount' => 'required|numeric|max:100',
        ]);
        $dataBorrowing = new Borrowing;
        $dataBorrowing->student_id = $request->student_id;
        $dataBorrowing->item_id = $request->item_id;
        $dataBorrowing->item_ammount = $request->item_ammount;
        $dataBorrowing->borrowing_date = \Carbon\Carbon::now();     
        $dataBorrowing->borrowing_date_return = $request->borrowing_date_return; 
        $dataBorrowing->save();
        
        $dataItem = Item::find($request->item_id);
        $dataItem->item_ammount = $dataItem->item_ammount - $request->item_ammount;
        $dataItem->save();
        return redirect()->route('borrowing.create')->with('success', 'Data berhasil dimasukan');
    }

    public function edit(Borrowing $borrowing){
        $dataItem= Item::select('item_id', 'item_name')->where('item_conditions', 'Baik')->where('item_ammount',">",0)->get();
        $dataStudent = Student::select('student_id', 'student_name')->get();
        return view('borrowing.detail', compact('borrowing','dataItem','dataStudent'));
    }

    public function update(Request $request){
        $dataBorrowing = Borrowing::find($request->borrowing_id);
        $dataBorrowing->status = $request->status;
        $dataBorrowing->borrowing_status = $request->borrowing_status;
        $dataBorrowing->save();
        return redirect()->route('borrowing')->with('success', 'Data berhasil diupdate');
    }


    public function delete(Request $request){
        $dataBorrowing = Borrowing::find($request->borrowing_id);
        $dataItem = Item::find($dataBorrowing->item_id['item_id']);
        $dataItem->item_ammount = $dataItem->item_ammount + $dataBorrowing->item_ammount;
        $dataItem->save();
        $dataBorrowing->delete();
        return redirect()->route('borrowing')->with('success', 'Data berhasil didelete');
    }

}