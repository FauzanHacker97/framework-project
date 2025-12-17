<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Find all movie collection ids owned by admin users
        $adminCollectionIds = DB::table('movie_collections')
            ->whereIn('user_id', function ($q) {
                $q->select('id')->from('users')->where('role', 'admin');
            })
            ->pluck('id')
            ->toArray();

        if (!empty($adminCollectionIds)) {
            // Delete related reviews first
            DB::table('reviews')->whereIn('movie_collection_id', $adminCollectionIds)->delete();

            // Delete the admin-owned collections
            DB::table('movie_collections')->whereIn('id', $adminCollectionIds)->delete();
        }
    }

    public function down(): void
    {
        // cannot restore deleted data
    }
};
