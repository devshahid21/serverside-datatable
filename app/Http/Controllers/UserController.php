<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
  
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $draw = $request->get('draw');
            $orderArray = $request->get('order');
            $columnNameArray = $request->get('columns');
            $searchArray = $request->get('search');
    
            $columnIndex = $orderArray[0]['column'] ?? 0;
            $columnName = $columnNameArray[$columnIndex]['data'] ?? 'id';
            $columnSortOrder = $orderArray[0]['dir'] ?? 'asc';
            $searchValue = $searchArray['value'] ?? '';
    
            $users = User::query();
    
            // Apply search scope if there is a search value
            if (!empty($searchValue)) {
                $users->search($searchValue);
            }
    
            $total = User::count(); // Total records without filtering
            $totalFiltered = $users->count(); // Total records after filtering
    
            $users = $users->orderBy($columnName, $columnSortOrder)
                           ->offset($request->get("start"))
                           ->limit($request->get("length"))
                           ->get();
    
            $response = [
                "draw" => intval($draw),
                "recordsTotal" => $total,
                "recordsFiltered" => $totalFiltered,
                "data" => $users,
            ];
    
            return response()->json($response);
        }
    
        return view('serverside-datatable');
    }
    

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('edit', compact('user'));
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
    
        return redirect()->route('serverside-data')->with('success', 'User updated successfully');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    
        return response()->json(['message' => 'User deleted successfully']);
    }
    



}

