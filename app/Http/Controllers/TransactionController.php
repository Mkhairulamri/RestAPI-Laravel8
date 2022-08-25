<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;
// use Illuminate\Support\Facades\Validator;
use Validator;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transaction = Transactions::orderBy('time','DESC')->get();
        $response = ([
            "Message" => "List Transaction Data Order By Time",
            "data" =>$transaction
        ]);
        return response()->json($response,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'amount' => 'required|numeric',
            'type' => ['required','in:expense,revenue']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors,422);
        }

        try{
            $transaction = Transactions::create($request->all());
            $response = [
                'message' => "Transaksi Berhasil Ditambah",
                'data' => $transaction
            ];

            return response()->json($response,201);

        }catch(QueryException $e){
            return response()->json([
                'message' => "failed".$e->errorinfo
            ]);
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
        $transaction = Transactions::findOrFail($id);

        $response =[
            'Transaction' => $transaction
        ];

        return response()->json($response,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $transaction = Transactions::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'amount' => 'required|numeric',
            'type' => ['required','in:expense,revenue']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors,422);
        }

        try{
            $transaction->update($request->all());
            $response = [
                'message' => "Transaksi Berhasil Diubah",
                'data' => $transaction
            ];

            return response()->json($response,200);

        }catch(QueryException $e){
            return response()->json([
                'message' => "failed".$e->errorinfo
            ]);
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
        $transaction = Transactions::findOrFail($id);

        try{
            $transaction->delete();

            $response = [
                "Message" => "pesan berhasil Dihapus"
            ];

            return response()->json($response,200);
        }catch(QueryException $e){
            return response()->json([
                'Message' => 'gagal'.$e->errorInfo
            ]);
        }
    }
}
