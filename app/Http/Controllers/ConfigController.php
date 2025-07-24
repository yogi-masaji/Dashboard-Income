<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\grupLokasi;
use App\Models\lokasi;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ConfigController extends Controller
{
    public function locationView()
    {
        return view('pages.formlocationtools');
    }

    public function getLocations(Request $request)
    {
        if ($request->ajax()) {
            // Using Eloquent to get all locations where active_status is 1
            // We join with ms_group to get the group name, as in the original query
            $data = lokasi::where('ms_lokasi.active_status', '1')
                ->join('ms_group', 'ms_group.id_Group', '=', 'ms_lokasi.id_Group')
                ->select('ms_lokasi.id_Lokasi', 'ms_lokasi.kode_Lokasi', 'ms_lokasi.nama_Lokasi', 'ms_lokasi.ip_Lokasi', 'ms_lokasi.system_code', 'ms_group.nama_Group')
                ->orderBy('ms_lokasi.id_Lokasi', 'desc')
                ->get();

            // Return data in a format that client-side DataTables can read
            return response()->json(['data' => $data]);
        }
    }

    public function storeLocation(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'kode_Lokasi' => 'required|string|max:255|unique:ms_lokasi,kode_Lokasi',
            'nama_Lokasi' => 'required|string|max:255',
            'ip_Lokasi'   => 'required|string',
            'system_code' => 'required|string|in:EZITAMA,PARKEE',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        // Create and save the new location
        $lokasi = lokasi::create([
            'kode_Lokasi' => $request->kode_Lokasi,
            'nama_Lokasi' => $request->nama_Lokasi,
            'ip_Lokasi'   => $request->ip_Lokasi,
            'system_code' => $request->system_code,
            'id_Group'    => '55', // Hardcoded as in the original script
            'db_migration' => '-', // Hardcoded as in the original script
        ]);

        if ($lokasi) {
            return response()->json(['status' => 'success', 'message' => 'Location created successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to create location.'], 500);
    }

    public function showLocation(lokasi $lokasi)
    {
        // The location is automatically fetched by Laravel's route-model binding
        return response()->json($lokasi);
    }

    public function updateLocation(Request $request, lokasi $lokasi)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'kode_Lokasi' => 'required|string|max:255|unique:ms_lokasi,kode_Lokasi,' . $lokasi->id_Lokasi . ',id_Lokasi',
            'nama_Lokasi' => 'required|string|max:255',
            'ip_Lokasi'   => 'required|string',
            'system_code' => 'required|string|in:EZITAMA,PARKEE',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        $originalNamaLokasi = $lokasi->nama_Lokasi;

        // Update the location
        $lokasi->kode_Lokasi = $request->kode_Lokasi;
        $lokasi->nama_Lokasi = $request->nama_Lokasi;
        $lokasi->ip_Lokasi = $request->ip_Lokasi;
        $lokasi->system_code = $request->system_code;
        $updateSuccess = $lokasi->save();

        if ($updateSuccess) {
            // As in the original script, update ms_login table as well.
            // It's better to use a model for ms_login, but for a direct conversion, DB facade is used.
            DB::table('ms_login')
                ->where('site_name', $originalNamaLokasi)
                ->update(['ip' => $request->ip_Lokasi]);

            return response()->json(['status' => 'success', 'message' => 'Location updated successfully.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Failed to update location.'], 500);
    }

    public function destroyLocation(lokasi $lokasi)
    {
        // The original script sets active_status to 0 instead of deleting.
        $lokasi->active_status = '0';
        $lokasi->save();

        return response()->json(['status' => 'success', 'message' => 'Location deleted successfully.']);
    }

    public function grupLokasiView()
    {
        return view('pages.gruplocation');
    }
    public function getGroups()
    {
        $groups = Group::where('id_Group', '!=', 58)->orderBy('id_Group', 'asc')->get();
        return response()->json($groups);
    }

    /**
     * Mengambil semua lokasi yang sudah terhubung dengan grup tertentu.
     */
    public function getGroupLocations(Request $request)
    {
        $id_Group = $request->input('id_Group');

        // TAMBAHKAN INI: Jika tidak ada grup yang dipilih (nilai 0 atau null),
        // kembalikan array JSON kosong dengan status 200 OK untuk mencegah error AJAX.
        if (!$id_Group || $id_Group == '0') {
            return response()->json([]);
        }

        $validator = Validator::make($request->all(), [
            'id_Group' => 'required|integer|exists:ms_group,id_Group',
        ]);

        if ($validator->fails()) {
            // Jika validasi gagal untuk nilai selain 0, kembalikan error.
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $data = grupLokasi::where('group_lokasi.id_Group', $id_Group)
            ->join('ms_lokasi', 'ms_lokasi.id_Lokasi', '=', 'group_lokasi.id_Lokasi')
            ->select('ms_lokasi.nama_Lokasi', 'group_lokasi.id_Glokasi')
            ->orderBy('group_lokasi.id_Glokasi', 'desc')
            ->get();

        return response()->json($data);
    }

    /**
     * Menghapus lokasi dari sebuah grup.
     */
    public function deleteGroupLocation($id)
    {
        $groupLocation = grupLokasi::find($id);
        if ($groupLocation) {
            $groupLocation->delete();
            return response()->json(['code' => 200, 'message' => 'Success delete location']);
        }
        return response()->json(['code' => 404, 'message' => 'Data not found'], 404);
    }

    /**
     * Mengambil lokasi yang *belum* ditambahkan ke grup tertentu.
     */
    public function getAvailableLocations(Request $request)
    {
        $selectedGroup = $request->input('selectedGroup');

        // Ambil ID lokasi yang sudah ada di grup
        $existingLocationIds = grupLokasi::where('id_Group', $selectedGroup)->pluck('id_Lokasi');

        // Ambil lokasi yang aktif dan belum ada di grup
        $locations = Lokasi::where('active_status', '1')
            ->whereNotIn('id_Lokasi', $existingLocationIds)
            ->orderBy('id_Lokasi', 'desc')
            ->select('id_Lokasi', 'nama_Lokasi')
            ->get();

        return response()->json($locations);
    }

    /**
     * Menyimpan beberapa lokasi ke dalam satu grup.
     */
    public function storeMultipleLocations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required|integer|exists:ms_group,id_Group',
            'location' => 'required|array',
            'location.*' => 'integer|exists:ms_lokasi,id_Lokasi',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $groupId = $request->input('group');
        $locations = $request->input('location');

        foreach ($locations as $locationId) {
            grupLokasi::create([
                'id_Group' => $groupId,
                'id_Lokasi' => $locationId,
            ]);
        }

        return response()->json(['message' => 'Berhasil Menambahkan']);
    }

    /**
     * Mengambil daftar semua grup untuk modal "Manage Group".
     */
    public function getAllGroups()
    {
        $groups = Group::orderBy('id_Group', 'desc')->get(['id_Group', 'nama_Group']);
        return response()->json($groups);
    }

    /**
     * Mengambil daftar semua menu.
     */
    public function getAllMenus()
    {
        $menus = Menu::orderBy('id_Menu', 'asc')->get(['id_Menu', 'nama_Menu']);
        return response()->json($menus);
    }

    /**
     * Menyimpan data grup baru beserta menu-menunya.
     */
    public function storeGroup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_Group' => 'required|string|max:255|unique:ms_group,nama_Group',
            'nama_Menu' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            $group = Group::create(['nama_Group' => $request->nama_Group]);

            $defaultMenus = range(1, 12);
            $selectedMenus = $request->input('nama_Menu', []);
            $menusToSync = array_unique(array_merge($defaultMenus, $selectedMenus));

            $group->menus()->sync($menusToSync);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Data group user berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mengambil data grup dan menu terkait untuk proses edit.
     */
    public function editGroup(Group $group)
    {
        // Memuat relasi menus dan hanya mengambil id_Menu
        $group->load(['menus:id_Menu']);
        $menuIds = $group->menus->pluck('id_Menu');

        return response()->json([
            'group' => $group,
            'menus' => $menuIds
        ]);
    }

    /**
     * Memperbarui data grup beserta menu-menunya.
     */
    public function updateGroup(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'nama_Group' => 'required|string|max:255|unique:ms_group,nama_Group,' . $group->id_Group . ',id_Group',
            'nama_Menu' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            $group->nama_Group = $request->nama_Group;
            $group->save();

            $defaultMenus = range(1, 12);
            $selectedMenus = $request->input('nama_Menu', []);
            $menusToSync = array_unique(array_merge($defaultMenus, $selectedMenus));

            // Sync akan menghapus relasi lama dan menyisipkan yang baru
            $group->menus()->sync($menusToSync);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Group updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update group: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus grup beserta relasinya.
     */
    public function destroyGroup(Group $group)
    {
        DB::beginTransaction();
        try {
            // Hapus relasi di group_menu
            $group->menus()->detach();
            // Hapus relasi di group_lokasi
            grupLokasi::where('id_Group', $group->id_Group)->delete();
            // Hapus grup itu sendiri
            $group->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Group deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to delete group: ' . $e->getMessage()], 500);
        }
    }
}
