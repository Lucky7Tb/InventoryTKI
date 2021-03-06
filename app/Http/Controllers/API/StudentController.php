<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Student;
use App\Borrowing;
use OneSignal;

class StudentController extends Controller
{
    public function savePlayerId(Request $request)
    {
        try {
            $dataStudent = Student::find($request->student_id);
            $dataStudent->player_id = $request->player_id;
            $dataStudent->save();            
            return response()->json([
                "message" => "Sukses",
                "serve" => $request->student_id
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Terjadi kesalahan pada server",
                "serve" => []
            ], 500);
        }
    }

    public function changePassword(Request $request){
        try {
            $dataStudent = Student::find($request->student_id);
            $newPassword = $request->student_password;
            $dataStudent->student_password = $newPassword;
            $dataStudent->save();
            return response()->json([
                "message" => "Password anda terlah berhasil diubah",
                "serve" => []
            ], 200);
        } catch (Exception $e) {
             return response()->json([
                "message" => "Terjadi kesalahan pada server",
                "serve" => []
            ], 500);
        }
    }

    public function sendNotif(Request $request){
        $dataBorrowing = Borrowing::where('borrowing_status', 'Dipinjam')->get();
        foreach ($dataBorrowing as $data) {
            $dataStudent = Student::where('student_id', $data->student_id['student_id'])->get();
            foreach ($dataStudent as $student) {
                OneSignal::sendNotificationToUser(
                    "Harap segera mengembalikan barang : " . $data->item_id['item_name' ]. " jika tidak ingin terkena denda!",
                    $student['player_id'],
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null
                );
            }
        }
        return response()->json([
            "message" => $dataBorrowing,
            "serve" => []
        ], 200);
    }
}
