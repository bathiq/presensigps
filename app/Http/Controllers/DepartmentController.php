<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $dept_name = $request->dept_name;
        $query = Department::query();
        $query->select('*');
        if (!empty($dept_name)) {
            $query->where('dept_name','like','%'.$dept_name.'%');
        }
        $department = $query->get();
        return view('department.index', compact('department'));
    }

    public function store(Request $request)
    {
        $dept_code = $request->dept_code;
        $dept_name = $request->dept_name;
        $data = [
            'dept_code' => $dept_code,
            'dept_name' => $dept_name
        ];

        $cek = DB::table('departments')->where('dept_code', $dept_code)->count();
        if ($cek > 0) {
            return Redirect::back()->with(['warning' => 'Data dengan Kode Departemen '. $dept_code.' Sudah ada!']);
        }
        $simpan = DB::table('departments')->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'Departemen Berhasil Disimpan']);
        }else{
            return Redirect::back()->with(['warning' => 'Departemen Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $dept_code = $request->dept_code;
        $department = DB::table('departments')->where('dept_code', $dept_code)->first();
        return view('department.edit', compact('department'));
    }

    public function update($dept_code, Request $request)
    {
        $dept_name = $request->dept_name;
        $data = [
            'dept_name' => $dept_name
        ];
        $update = DB::table('departments')->where('dept_code', $dept_code)->update($data);
        if ($update) {
            return Redirect::back()->with(['success' => 'Departemen Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Departemen Gagal Diupdate']);
        }
    }

    public function delete($dept_code)
    {
        $hapus = DB::table('departments')->where('dept_code', $dept_code)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Departemen Berhasil Dihapus']);
        }else{
            return Redirect::back()->with(['warning' => 'Departemen Gagal Dihapus']);
        }
    }
}
