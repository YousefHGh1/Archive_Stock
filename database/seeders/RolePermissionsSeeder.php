<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assignments = [
            // ======================== Original Permission Assignments ========================
            ['permission_id' => 1, 'role_id' => 2],
            ['permission_id' => 1, 'role_id' => 5],
            ['permission_id' => 1, 'role_id' => 6],
            ['permission_id' => 2, 'role_id' => 2],
            ['permission_id' => 2, 'role_id' => 5],
            ['permission_id' => 2, 'role_id' => 6],
            ['permission_id' => 3, 'role_id' => 2],
            ['permission_id' => 3, 'role_id' => 5],
            ['permission_id' => 3, 'role_id' => 6],
            ['permission_id' => 4, 'role_id' => 2],
            ['permission_id' => 4, 'role_id' => 5],
            ['permission_id' => 4, 'role_id' => 6],
            ['permission_id' => 5, 'role_id' => 2],
            ['permission_id' => 5, 'role_id' => 5],
            ['permission_id' => 5, 'role_id' => 6],
            ['permission_id' => 9, 'role_id' => 2],
            ['permission_id' => 9, 'role_id' => 6],
            ['permission_id' => 9, 'role_id' => 12],
            ['permission_id' => 10, 'role_id' => 2],
            ['permission_id' => 11, 'role_id' => 2],
            ['permission_id' => 12, 'role_id' => 2],
            ['permission_id' => 12, 'role_id' => 5],
            ['permission_id' => 12, 'role_id' => 6],
            ['permission_id' => 14, 'role_id' => 2],
            ['permission_id' => 14, 'role_id' => 3],
            ['permission_id' => 14, 'role_id' => 6],
            ['permission_id' => 15, 'role_id' => 2],
            ['permission_id' => 15, 'role_id' => 6],
            ['permission_id' => 15, 'role_id' => 7],
            ['permission_id' => 16, 'role_id' => 2],
            ['permission_id' => 16, 'role_id' => 6],
            ['permission_id' => 16, 'role_id' => 8],
            ['permission_id' => 17, 'role_id' => 2],
            ['permission_id' => 17, 'role_id' => 6],
            ['permission_id' => 17, 'role_id' => 9],
            ['permission_id' => 18, 'role_id' => 2],
            ['permission_id' => 18, 'role_id' => 6],
            ['permission_id' => 18, 'role_id' => 10],
            ['permission_id' => 19, 'role_id' => 2],
            ['permission_id' => 23, 'role_id' => 2],
            ['permission_id' => 23, 'role_id' => 5],
            ['permission_id' => 23, 'role_id' => 6],
            ['permission_id' => 24, 'role_id' => 2],
            ['permission_id' => 24, 'role_id' => 3],
            ['permission_id' => 24, 'role_id' => 6],
            ['permission_id' => 24, 'role_id' => 7],
            ['permission_id' => 24, 'role_id' => 8],
            ['permission_id' => 24, 'role_id' => 9],
            ['permission_id' => 24, 'role_id' => 10],
            ['permission_id' => 25, 'role_id' => 2],
            ['permission_id' => 26, 'role_id' => 2],
            ['permission_id' => 26, 'role_id' => 5],
            ['permission_id' => 26, 'role_id' => 6],
            ['permission_id' => 27, 'role_id' => 2],
            ['permission_id' => 27, 'role_id' => 5],
            ['permission_id' => 27, 'role_id' => 6],
            ['permission_id' => 28, 'role_id' => 2],
            ['permission_id' => 28, 'role_id' => 3],
            ['permission_id' => 28, 'role_id' => 6],
            ['permission_id' => 28, 'role_id' => 7],
            ['permission_id' => 28, 'role_id' => 8],
            ['permission_id' => 28, 'role_id' => 9],
            ['permission_id' => 28, 'role_id' => 10],
            ['permission_id' => 29, 'role_id' => 2],
            ['permission_id' => 29, 'role_id' => 5],
            ['permission_id' => 29, 'role_id' => 6],
            ['permission_id' => 30, 'role_id' => 2],
            ['permission_id' => 30, 'role_id' => 5],
            ['permission_id' => 30, 'role_id' => 6],
            ['permission_id' => 31, 'role_id' => 2],
            ['permission_id' => 31, 'role_id' => 5],
            ['permission_id' => 31, 'role_id' => 6],
            ['permission_id' => 33, 'role_id' => 2],
            ['permission_id' => 33, 'role_id' => 5],
            ['permission_id' => 33, 'role_id' => 6],
            ['permission_id' => 34, 'role_id' => 2],
            ['permission_id' => 34, 'role_id' => 6],
            ['permission_id' => 37, 'role_id' => 2],
            ['permission_id' => 38, 'role_id' => 2],
            ['permission_id' => 38, 'role_id' => 5],
            ['permission_id' => 38, 'role_id' => 6],
            ['permission_id' => 39, 'role_id' => 2],
            ['permission_id' => 39, 'role_id' => 5],
            ['permission_id' => 39, 'role_id' => 6],

            // ======================== GRANULAR PERMISSIONS FOR ADMIN (role_id=2) ========================
            ['permission_id' => 40, 'role_id' => 2], ['permission_id' => 41, 'role_id' => 2],
            ['permission_id' => 42, 'role_id' => 2], ['permission_id' => 43, 'role_id' => 2],
            ['permission_id' => 44, 'role_id' => 2], ['permission_id' => 45, 'role_id' => 2],
            ['permission_id' => 46, 'role_id' => 2], ['permission_id' => 47, 'role_id' => 2],
            ['permission_id' => 48, 'role_id' => 2], ['permission_id' => 49, 'role_id' => 2],
            ['permission_id' => 50, 'role_id' => 2], ['permission_id' => 51, 'role_id' => 2],
            ['permission_id' => 52, 'role_id' => 2], ['permission_id' => 53, 'role_id' => 2],
            ['permission_id' => 54, 'role_id' => 2], ['permission_id' => 55, 'role_id' => 2],
            ['permission_id' => 56, 'role_id' => 2], ['permission_id' => 57, 'role_id' => 2],
            ['permission_id' => 58, 'role_id' => 2], ['permission_id' => 59, 'role_id' => 2],
            ['permission_id' => 60, 'role_id' => 2], ['permission_id' => 61, 'role_id' => 2],
            ['permission_id' => 62, 'role_id' => 2], ['permission_id' => 63, 'role_id' => 2],
            ['permission_id' => 64, 'role_id' => 2], ['permission_id' => 65, 'role_id' => 2],
            ['permission_id' => 66, 'role_id' => 2], ['permission_id' => 67, 'role_id' => 2],
            ['permission_id' => 68, 'role_id' => 2], ['permission_id' => 69, 'role_id' => 2],
            ['permission_id' => 70, 'role_id' => 2], ['permission_id' => 71, 'role_id' => 2],
            ['permission_id' => 72, 'role_id' => 2], ['permission_id' => 73, 'role_id' => 2],
            ['permission_id' => 74, 'role_id' => 2], ['permission_id' => 75, 'role_id' => 2],
            ['permission_id' => 76, 'role_id' => 2], ['permission_id' => 77, 'role_id' => 2],
            ['permission_id' => 78, 'role_id' => 2], ['permission_id' => 79, 'role_id' => 2],
            ['permission_id' => 80, 'role_id' => 2], ['permission_id' => 81, 'role_id' => 2],
            ['permission_id' => 82, 'role_id' => 2], ['permission_id' => 83, 'role_id' => 2],
            ['permission_id' => 84, 'role_id' => 2], ['permission_id' => 85, 'role_id' => 2],
            ['permission_id' => 86, 'role_id' => 2], ['permission_id' => 87, 'role_id' => 2],
            ['permission_id' => 88, 'role_id' => 2], ['permission_id' => 89, 'role_id' => 2],
            ['permission_id' => 90, 'role_id' => 2], ['permission_id' => 91, 'role_id' => 2],
            ['permission_id' => 92, 'role_id' => 2], ['permission_id' => 93, 'role_id' => 2],
            ['permission_id' => 94, 'role_id' => 2], ['permission_id' => 95, 'role_id' => 2],

            // ======================== GRANULAR PERMISSIONS FOR STOCK (role_id=5) ========================
            ['permission_id' => 40, 'role_id' => 5], ['permission_id' => 41, 'role_id' => 5],
            ['permission_id' => 42, 'role_id' => 5], ['permission_id' => 43, 'role_id' => 5],
            ['permission_id' => 44, 'role_id' => 5], ['permission_id' => 45, 'role_id' => 5],
            ['permission_id' => 46, 'role_id' => 5], ['permission_id' => 47, 'role_id' => 5],
            ['permission_id' => 48, 'role_id' => 5], ['permission_id' => 49, 'role_id' => 5],
            ['permission_id' => 50, 'role_id' => 5], ['permission_id' => 51, 'role_id' => 5],
            ['permission_id' => 52, 'role_id' => 5], ['permission_id' => 53, 'role_id' => 5],
            ['permission_id' => 54, 'role_id' => 5], ['permission_id' => 55, 'role_id' => 5],
            ['permission_id' => 56, 'role_id' => 5], ['permission_id' => 57, 'role_id' => 5],
            ['permission_id' => 58, 'role_id' => 5], ['permission_id' => 59, 'role_id' => 5],
            ['permission_id' => 60, 'role_id' => 5], ['permission_id' => 61, 'role_id' => 5],
            ['permission_id' => 62, 'role_id' => 5], ['permission_id' => 63, 'role_id' => 5],
            ['permission_id' => 64, 'role_id' => 5], ['permission_id' => 65, 'role_id' => 5],
            ['permission_id' => 66, 'role_id' => 5], ['permission_id' => 67, 'role_id' => 5],
            ['permission_id' => 68, 'role_id' => 5], ['permission_id' => 69, 'role_id' => 5],
            ['permission_id' => 70, 'role_id' => 5], ['permission_id' => 71, 'role_id' => 5],
            ['permission_id' => 72, 'role_id' => 5], ['permission_id' => 73, 'role_id' => 5],
            ['permission_id' => 74, 'role_id' => 5], ['permission_id' => 75, 'role_id' => 5],
            ['permission_id' => 76, 'role_id' => 5], ['permission_id' => 77, 'role_id' => 5],
            ['permission_id' => 78, 'role_id' => 5], ['permission_id' => 79, 'role_id' => 5],
            ['permission_id' => 80, 'role_id' => 5], ['permission_id' => 81, 'role_id' => 5],
            ['permission_id' => 82, 'role_id' => 5], ['permission_id' => 83, 'role_id' => 5],
            ['permission_id' => 84, 'role_id' => 5], ['permission_id' => 85, 'role_id' => 5],
            ['permission_id' => 86, 'role_id' => 5], ['permission_id' => 87, 'role_id' => 5],
            ['permission_id' => 88, 'role_id' => 5], ['permission_id' => 89, 'role_id' => 5],
            ['permission_id' => 90, 'role_id' => 5], ['permission_id' => 91, 'role_id' => 5],
            ['permission_id' => 92, 'role_id' => 5], ['permission_id' => 93, 'role_id' => 5],
            ['permission_id' => 94, 'role_id' => 5], ['permission_id' => 95, 'role_id' => 5],

            // ======================== GRANULAR PERMISSIONS FOR Super-User (role_id=6) ========================
            ['permission_id' => 40, 'role_id' => 6], ['permission_id' => 41, 'role_id' => 6],
            ['permission_id' => 42, 'role_id' => 6], ['permission_id' => 43, 'role_id' => 6],
            ['permission_id' => 44, 'role_id' => 6], ['permission_id' => 45, 'role_id' => 6],
            ['permission_id' => 46, 'role_id' => 6], ['permission_id' => 47, 'role_id' => 6],
            ['permission_id' => 48, 'role_id' => 6], ['permission_id' => 49, 'role_id' => 6],
            ['permission_id' => 50, 'role_id' => 6], ['permission_id' => 51, 'role_id' => 6],
            ['permission_id' => 52, 'role_id' => 6], ['permission_id' => 53, 'role_id' => 6],
            ['permission_id' => 54, 'role_id' => 6], ['permission_id' => 55, 'role_id' => 6],
            ['permission_id' => 56, 'role_id' => 6], ['permission_id' => 57, 'role_id' => 6],
            ['permission_id' => 58, 'role_id' => 6], ['permission_id' => 59, 'role_id' => 6],
            ['permission_id' => 60, 'role_id' => 6], ['permission_id' => 61, 'role_id' => 6],
            ['permission_id' => 62, 'role_id' => 6], ['permission_id' => 63, 'role_id' => 6],
            ['permission_id' => 64, 'role_id' => 6], ['permission_id' => 65, 'role_id' => 6],
            ['permission_id' => 66, 'role_id' => 6], ['permission_id' => 67, 'role_id' => 6],
            ['permission_id' => 68, 'role_id' => 6], ['permission_id' => 69, 'role_id' => 6],
            ['permission_id' => 70, 'role_id' => 6], ['permission_id' => 71, 'role_id' => 6],
            ['permission_id' => 72, 'role_id' => 6], ['permission_id' => 73, 'role_id' => 6],
            ['permission_id' => 74, 'role_id' => 6], ['permission_id' => 75, 'role_id' => 6],
            ['permission_id' => 76, 'role_id' => 6], ['permission_id' => 77, 'role_id' => 6],
            ['permission_id' => 78, 'role_id' => 6], ['permission_id' => 79, 'role_id' => 6],
            ['permission_id' => 80, 'role_id' => 6], ['permission_id' => 81, 'role_id' => 6],
            ['permission_id' => 82, 'role_id' => 6], ['permission_id' => 83, 'role_id' => 6],
            ['permission_id' => 84, 'role_id' => 6], ['permission_id' => 85, 'role_id' => 6],
            ['permission_id' => 86, 'role_id' => 6], ['permission_id' => 87, 'role_id' => 6],
            ['permission_id' => 88, 'role_id' => 6], ['permission_id' => 89, 'role_id' => 6],
            ['permission_id' => 90, 'role_id' => 6], ['permission_id' => 91, 'role_id' => 6],
            ['permission_id' => 92, 'role_id' => 6], ['permission_id' => 93, 'role_id' => 6],
            ['permission_id' => 94, 'role_id' => 6], ['permission_id' => 95, 'role_id' => 6],

            // ======================== MODULE MANAGER ROLES ========================
            ['permission_id' => 48, 'role_id' => 13], ['permission_id' => 49, 'role_id' => 13],
            ['permission_id' => 50, 'role_id' => 13], ['permission_id' => 51, 'role_id' => 13],

            ['permission_id' => 44, 'role_id' => 14], ['permission_id' => 45, 'role_id' => 14],
            ['permission_id' => 46, 'role_id' => 14], ['permission_id' => 47, 'role_id' => 14],

            ['permission_id' => 40, 'role_id' => 15], ['permission_id' => 41, 'role_id' => 15],
            ['permission_id' => 42, 'role_id' => 15], ['permission_id' => 43, 'role_id' => 15],

            ['permission_id' => 52, 'role_id' => 16], ['permission_id' => 53, 'role_id' => 16],
            ['permission_id' => 54, 'role_id' => 16], ['permission_id' => 55, 'role_id' => 16],

            ['permission_id' => 56, 'role_id' => 17], ['permission_id' => 57, 'role_id' => 17],
            ['permission_id' => 58, 'role_id' => 17], ['permission_id' => 59, 'role_id' => 17],

            ['permission_id' => 60, 'role_id' => 18], ['permission_id' => 61, 'role_id' => 18],
            ['permission_id' => 62, 'role_id' => 18], ['permission_id' => 63, 'role_id' => 18],

            ['permission_id' => 64, 'role_id' => 19], ['permission_id' => 65, 'role_id' => 19],
            ['permission_id' => 66, 'role_id' => 19], ['permission_id' => 67, 'role_id' => 19],

            ['permission_id' => 68, 'role_id' => 20], ['permission_id' => 69, 'role_id' => 20],
            ['permission_id' => 70, 'role_id' => 20], ['permission_id' => 71, 'role_id' => 20],

            ['permission_id' => 72, 'role_id' => 21], ['permission_id' => 73, 'role_id' => 21],
            ['permission_id' => 74, 'role_id' => 21], ['permission_id' => 75, 'role_id' => 21],

            ['permission_id' => 76, 'role_id' => 22], ['permission_id' => 77, 'role_id' => 22],
            ['permission_id' => 78, 'role_id' => 22], ['permission_id' => 79, 'role_id' => 22],

            ['permission_id' => 80, 'role_id' => 23], ['permission_id' => 81, 'role_id' => 23],
            ['permission_id' => 82, 'role_id' => 23], ['permission_id' => 83, 'role_id' => 23],

            ['permission_id' => 84, 'role_id' => 24], ['permission_id' => 85, 'role_id' => 24],
            ['permission_id' => 86, 'role_id' => 24], ['permission_id' => 87, 'role_id' => 24],

            ['permission_id' => 88, 'role_id' => 25], ['permission_id' => 89, 'role_id' => 25],
            ['permission_id' => 90, 'role_id' => 25], ['permission_id' => 91, 'role_id' => 25],

            ['permission_id' => 92, 'role_id' => 26], ['permission_id' => 93, 'role_id' => 26],
            ['permission_id' => 94, 'role_id' => 26], ['permission_id' => 95, 'role_id' => 26],

            // ======================== NEW GRANULAR PERMISSIONS FOR SPECIALIZED ROLES ========================
            // Archive permissions for archive role (role_id=3)
            ['permission_id' => 101, 'role_id' => 3], ['permission_id' => 102, 'role_id' => 3],
            ['permission_id' => 103, 'role_id' => 3], ['permission_id' => 104, 'role_id' => 3],

            // Legal permissions for legal role (role_id=7)
            ['permission_id' => 105, 'role_id' => 7], ['permission_id' => 106, 'role_id' => 7],
            ['permission_id' => 107, 'role_id' => 7], ['permission_id' => 108, 'role_id' => 7],

            // Computer permissions for computer role (role_id=8)
            ['permission_id' => 109, 'role_id' => 8], ['permission_id' => 110, 'role_id' => 8],
            ['permission_id' => 111, 'role_id' => 8], ['permission_id' => 112, 'role_id' => 8],

            // Censorship permissions for censorship role (role_id=10)
            ['permission_id' => 113, 'role_id' => 10], ['permission_id' => 114, 'role_id' => 10],
            ['permission_id' => 115, 'role_id' => 10], ['permission_id' => 116, 'role_id' => 10],

            // Jibaya permissions for jibaya role (role_id=9)
            ['permission_id' => 117, 'role_id' => 9], ['permission_id' => 118, 'role_id' => 9],
            ['permission_id' => 119, 'role_id' => 9], ['permission_id' => 120, 'role_id' => 9],

            // Suppliers permissions for suppliers-manager role (role_id=16)
            ['permission_id' => 121, 'role_id' => 16], ['permission_id' => 122, 'role_id' => 16],
            ['permission_id' => 123, 'role_id' => 16], ['permission_id' => 124, 'role_id' => 16],

            // Users permissions for users role (role_id=12)
            ['permission_id' => 125, 'role_id' => 12], ['permission_id' => 126, 'role_id' => 12],
            ['permission_id' => 127, 'role_id' => 12], ['permission_id' => 128, 'role_id' => 12],

            // ======================== ALL NEW PERMISSIONS FOR ADMIN (role_id=2) ========================
            ['permission_id' => 101, 'role_id' => 2], ['permission_id' => 102, 'role_id' => 2],
            ['permission_id' => 103, 'role_id' => 2], ['permission_id' => 104, 'role_id' => 2],
            ['permission_id' => 105, 'role_id' => 2], ['permission_id' => 106, 'role_id' => 2],
            ['permission_id' => 107, 'role_id' => 2], ['permission_id' => 108, 'role_id' => 2],
            ['permission_id' => 109, 'role_id' => 2], ['permission_id' => 110, 'role_id' => 2],
            ['permission_id' => 111, 'role_id' => 2], ['permission_id' => 112, 'role_id' => 2],
            ['permission_id' => 113, 'role_id' => 2], ['permission_id' => 114, 'role_id' => 2],
            ['permission_id' => 115, 'role_id' => 2], ['permission_id' => 116, 'role_id' => 2],
            ['permission_id' => 117, 'role_id' => 2], ['permission_id' => 118, 'role_id' => 2],
            ['permission_id' => 119, 'role_id' => 2], ['permission_id' => 120, 'role_id' => 2],
            ['permission_id' => 121, 'role_id' => 2], ['permission_id' => 122, 'role_id' => 2],
            ['permission_id' => 123, 'role_id' => 2], ['permission_id' => 124, 'role_id' => 2],
            ['permission_id' => 125, 'role_id' => 2], ['permission_id' => 126, 'role_id' => 2],
            ['permission_id' => 127, 'role_id' => 2], ['permission_id' => 128, 'role_id' => 2],

            // ======================== ALL NEW PERMISSIONS FOR SUPER-USER (role_id=6) ========================
            ['permission_id' => 101, 'role_id' => 6], ['permission_id' => 102, 'role_id' => 6],
            ['permission_id' => 103, 'role_id' => 6], ['permission_id' => 104, 'role_id' => 6],
            ['permission_id' => 105, 'role_id' => 6], ['permission_id' => 106, 'role_id' => 6],
            ['permission_id' => 107, 'role_id' => 6], ['permission_id' => 108, 'role_id' => 6],
            ['permission_id' => 109, 'role_id' => 6], ['permission_id' => 110, 'role_id' => 6],
            ['permission_id' => 111, 'role_id' => 6], ['permission_id' => 112, 'role_id' => 6],

            // ======================== FULL FUEL ACCESS ROLE (role_id=40) ========================
            // Diesel permissions
            ['permission_id' => 76, 'role_id' => 40], ['permission_id' => 77, 'role_id' => 40],
            ['permission_id' => 78, 'role_id' => 40], ['permission_id' => 79, 'role_id' => 40],
            // Diesel Export permissions
            ['permission_id' => 80, 'role_id' => 40], ['permission_id' => 81, 'role_id' => 40],
            ['permission_id' => 82, 'role_id' => 40], ['permission_id' => 83, 'role_id' => 40],
            // Suppliers permissions
            ['permission_id' => 121, 'role_id' => 40], ['permission_id' => 122, 'role_id' => 40],
            ['permission_id' => 123, 'role_id' => 40], ['permission_id' => 124, 'role_id' => 40],
            // Sections permissions (for departments)
            ['permission_id' => 88, 'role_id' => 40], ['permission_id' => 89, 'role_id' => 40],
            ['permission_id' => 90, 'role_id' => 40], ['permission_id' => 91, 'role_id' => 40],

            // ======================== FULL STOCK ACCESS ROLE (role_id=41) ========================
            // Units permissions
            ['permission_id' => 40, 'role_id' => 41], ['permission_id' => 41, 'role_id' => 41],
            ['permission_id' => 42, 'role_id' => 41], ['permission_id' => 43, 'role_id' => 41],
            // Categories permissions
            ['permission_id' => 44, 'role_id' => 41], ['permission_id' => 45, 'role_id' => 41],
            ['permission_id' => 46, 'role_id' => 41], ['permission_id' => 47, 'role_id' => 41],
            // Items permissions
            ['permission_id' => 48, 'role_id' => 41], ['permission_id' => 49, 'role_id' => 41],
            ['permission_id' => 50, 'role_id' => 41], ['permission_id' => 51, 'role_id' => 41],
            // Supplier Items permissions
            ['permission_id' => 52, 'role_id' => 41], ['permission_id' => 53, 'role_id' => 41],
            ['permission_id' => 54, 'role_id' => 41], ['permission_id' => 55, 'role_id' => 41],
            // Invoices permissions
            ['permission_id' => 56, 'role_id' => 41], ['permission_id' => 57, 'role_id' => 41],
            ['permission_id' => 58, 'role_id' => 41], ['permission_id' => 59, 'role_id' => 41],
            // Invoice Exports permissions
            ['permission_id' => 60, 'role_id' => 41], ['permission_id' => 61, 'role_id' => 41],
            ['permission_id' => 62, 'role_id' => 41], ['permission_id' => 63, 'role_id' => 41],
            // Custody permissions
            ['permission_id' => 64, 'role_id' => 41], ['permission_id' => 65, 'role_id' => 41],
            ['permission_id' => 66, 'role_id' => 41], ['permission_id' => 67, 'role_id' => 41],
            // Currency permissions
            ['permission_id' => 68, 'role_id' => 41], ['permission_id' => 69, 'role_id' => 41],
            ['permission_id' => 70, 'role_id' => 41], ['permission_id' => 71, 'role_id' => 41],
            // Employee permissions
            ['permission_id' => 72, 'role_id' => 41], ['permission_id' => 73, 'role_id' => 41],
            ['permission_id' => 74, 'role_id' => 41], ['permission_id' => 75, 'role_id' => 41],
            // Stores permissions
            ['permission_id' => 92, 'role_id' => 41], ['permission_id' => 93, 'role_id' => 41],
            ['permission_id' => 94, 'role_id' => 41], ['permission_id' => 95, 'role_id' => 41],
            // Sections permissions
            ['permission_id' => 88, 'role_id' => 41], ['permission_id' => 89, 'role_id' => 41],
            ['permission_id' => 90, 'role_id' => 41], ['permission_id' => 91, 'role_id' => 41],
            // Suppliers permissions
            ['permission_id' => 121, 'role_id' => 41], ['permission_id' => 122, 'role_id' => 41],
            ['permission_id' => 123, 'role_id' => 41], ['permission_id' => 124, 'role_id' => 41],

            // ======================== FULL PURCHASE ACCESS ROLE (role_id=42) ========================
            // Purchase permissions
            ['permission_id' => 96, 'role_id' => 42],
            ['permission_id' => 97, 'role_id' => 42], ['permission_id' => 98, 'role_id' => 42],
            ['permission_id' => 99, 'role_id' => 42], ['permission_id' => 100, 'role_id' => 42],
            // Report permissions
            ['permission_id' => 84, 'role_id' => 42], ['permission_id' => 85, 'role_id' => 42],
            ['permission_id' => 86, 'role_id' => 42], ['permission_id' => 87, 'role_id' => 42],
            // Suppliers permissions
            ['permission_id' => 121, 'role_id' => 42], ['permission_id' => 122, 'role_id' => 42],
            ['permission_id' => 123, 'role_id' => 42], ['permission_id' => 124, 'role_id' => 42],

            // ======================== FULL VEHICLE ACCESS ROLE (role_id=43) ========================
            // Vehicles permissions
            ['permission_id' => 135, 'role_id' => 43], ['permission_id' => 136, 'role_id' => 43],
            ['permission_id' => 137, 'role_id' => 43], ['permission_id' => 138, 'role_id' => 43],
            ['permission_id' => 139, 'role_id' => 43],
            // Drivers permissions
            ['permission_id' => 140, 'role_id' => 43], ['permission_id' => 141, 'role_id' => 43],
            ['permission_id' => 142, 'role_id' => 43], ['permission_id' => 143, 'role_id' => 43],
            ['permission_id' => 144, 'role_id' => 43],
            // Vehicle Operations permissions
            ['permission_id' => 145, 'role_id' => 43], ['permission_id' => 146, 'role_id' => 43],
            ['permission_id' => 147, 'role_id' => 43], ['permission_id' => 148, 'role_id' => 43],
            ['permission_id' => 149, 'role_id' => 43],
            // Vehicle Reports permissions
            ['permission_id' => 150, 'role_id' => 43], ['permission_id' => 151, 'role_id' => 43],
            ['permission_id' => 113, 'role_id' => 6], ['permission_id' => 114, 'role_id' => 6],
            ['permission_id' => 115, 'role_id' => 6], ['permission_id' => 116, 'role_id' => 6],
            ['permission_id' => 117, 'role_id' => 6], ['permission_id' => 118, 'role_id' => 6],
            ['permission_id' => 119, 'role_id' => 6], ['permission_id' => 120, 'role_id' => 6],
            ['permission_id' => 121, 'role_id' => 6], ['permission_id' => 122, 'role_id' => 6],
            ['permission_id' => 123, 'role_id' => 6], ['permission_id' => 124, 'role_id' => 6],
            ['permission_id' => 125, 'role_id' => 6], ['permission_id' => 126, 'role_id' => 6],
            ['permission_id' => 127, 'role_id' => 6], ['permission_id' => 128, 'role_id' => 6],

            // ======================== NEW MANAGER ROLES PERMISSIONS ========================
            // Archive manager permissions (role_id=28)
            ['permission_id' => 101, 'role_id' => 28], ['permission_id' => 102, 'role_id' => 28],
            ['permission_id' => 103, 'role_id' => 28], ['permission_id' => 104, 'role_id' => 28],

            // Legal manager permissions (role_id=29)
            ['permission_id' => 105, 'role_id' => 29], ['permission_id' => 106, 'role_id' => 29],
            ['permission_id' => 107, 'role_id' => 29], ['permission_id' => 108, 'role_id' => 29],

            // Computer manager permissions (role_id=30)
            ['permission_id' => 109, 'role_id' => 30], ['permission_id' => 110, 'role_id' => 30],
            ['permission_id' => 111, 'role_id' => 30], ['permission_id' => 112, 'role_id' => 30],

            // Censorship manager permissions (role_id=31)
            ['permission_id' => 113, 'role_id' => 31], ['permission_id' => 114, 'role_id' => 31],
            ['permission_id' => 115, 'role_id' => 31], ['permission_id' => 116, 'role_id' => 31],

            // Jibaya manager permissions (role_id=32)
            ['permission_id' => 117, 'role_id' => 32], ['permission_id' => 118, 'role_id' => 32],
            ['permission_id' => 119, 'role_id' => 32], ['permission_id' => 120, 'role_id' => 32],

            // ======================== SECTION-BASED PERMISSIONS ========================
            // Admin and Super-User get all section permissions
            ['permission_id' => 129, 'role_id' => 2], ['permission_id' => 130, 'role_id' => 2],
            ['permission_id' => 131, 'role_id' => 2], ['permission_id' => 132, 'role_id' => 2],
            ['permission_id' => 133, 'role_id' => 2], ['permission_id' => 134, 'role_id' => 2],

            ['permission_id' => 129, 'role_id' => 6], ['permission_id' => 130, 'role_id' => 6],
            ['permission_id' => 131, 'role_id' => 6], ['permission_id' => 132, 'role_id' => 6],
            ['permission_id' => 133, 'role_id' => 6], ['permission_id' => 134, 'role_id' => 6],

            // Section managers get view-own-section and manage-own-section
            ['permission_id' => 129, 'role_id' => 25], ['permission_id' => 131, 'role_id' => 25],

            // ======================== STOCK & FUEL SPECIALIZED ROLES ========================
            // ======================== READ-ONLY ROLE (role_id=36) ========================
            // Stock read permissions
            ['permission_id' => 41, 'role_id' => 36],  // units-read
            ['permission_id' => 45, 'role_id' => 36],  // categories-read
            ['permission_id' => 49, 'role_id' => 36],  // items-read
            ['permission_id' => 53, 'role_id' => 36],  // supplier_items-read
            ['permission_id' => 57, 'role_id' => 36],  // invoices-read
            ['permission_id' => 61, 'role_id' => 36],  // invoice_exports-read
            ['permission_id' => 65, 'role_id' => 36],  // custody-read
            ['permission_id' => 69, 'role_id' => 36],  // currency-read
            ['permission_id' => 73, 'role_id' => 36],  // employee-read
            ['permission_id' => 89, 'role_id' => 36],  // sections-read
            ['permission_id' => 93, 'role_id' => 36],  // stores-read
            ['permission_id' => 122, 'role_id' => 36], // suppliers-read
            // Fuel read permissions
            ['permission_id' => 77, 'role_id' => 36],  // diesels-read
            ['permission_id' => 81, 'role_id' => 36],  // dieselexport-read
            ['permission_id' => 85, 'role_id' => 36],  // report-read
            // Base access permissions
            ['permission_id' => 26, 'role_id' => 36],  // stock
            ['permission_id' => 12, 'role_id' => 36],  // diesels

            // ======================== CREATE-ONLY ROLE (role_id=37) ========================
            // Stock create permissions
            ['permission_id' => 40, 'role_id' => 37],  // units-create
            ['permission_id' => 44, 'role_id' => 37],  // categories-create
            ['permission_id' => 48, 'role_id' => 37],  // items-create
            ['permission_id' => 52, 'role_id' => 37],  // supplier_items-create
            ['permission_id' => 56, 'role_id' => 37],  // invoices-create
            ['permission_id' => 60, 'role_id' => 37],  // invoice_exports-create
            ['permission_id' => 64, 'role_id' => 37],  // custody-create
            ['permission_id' => 68, 'role_id' => 37],  // currency-create
            ['permission_id' => 72, 'role_id' => 37],  // employee-create
            ['permission_id' => 88, 'role_id' => 37],  // sections-create
            ['permission_id' => 92, 'role_id' => 37],  // stores-create
            ['permission_id' => 121, 'role_id' => 37], // suppliers-create
            // Fuel create permissions
            ['permission_id' => 76, 'role_id' => 37],  // diesels-create
            ['permission_id' => 80, 'role_id' => 37],  // dieselexport-create
            ['permission_id' => 84, 'role_id' => 37],  // report-create
            // Base access permissions
            ['permission_id' => 26, 'role_id' => 37],  // stock
            ['permission_id' => 12, 'role_id' => 37],  // diesels

            // ======================== UPDATE-ONLY ROLE (role_id=38) ========================
            // Stock update permissions
            ['permission_id' => 42, 'role_id' => 38],  // units-update
            ['permission_id' => 46, 'role_id' => 38],  // categories-update
            ['permission_id' => 50, 'role_id' => 38],  // items-update
            ['permission_id' => 54, 'role_id' => 38],  // supplier_items-update
            ['permission_id' => 58, 'role_id' => 38],  // invoices-update
            ['permission_id' => 62, 'role_id' => 38],  // invoice_exports-update
            ['permission_id' => 66, 'role_id' => 38],  // custody-update
            ['permission_id' => 70, 'role_id' => 38],  // currency-update
            ['permission_id' => 74, 'role_id' => 38],  // employee-update
            ['permission_id' => 90, 'role_id' => 38],  // sections-update
            ['permission_id' => 94, 'role_id' => 38],  // stores-update
            ['permission_id' => 123, 'role_id' => 38], // suppliers-update
            // Fuel update permissions
            ['permission_id' => 78, 'role_id' => 38],  // diesels-update
            ['permission_id' => 82, 'role_id' => 38],  // dieselexport-update
            ['permission_id' => 86, 'role_id' => 38],  // report-update
            // Base access permissions
            ['permission_id' => 26, 'role_id' => 38],  // stock
            ['permission_id' => 12, 'role_id' => 38],  // diesels

            // ======================== DELETE-ONLY ROLE (role_id=39) ========================
            // Stock delete permissions
            ['permission_id' => 43, 'role_id' => 39],  // units-delete
            ['permission_id' => 47, 'role_id' => 39],  // categories-delete
            ['permission_id' => 51, 'role_id' => 39],  // items-delete
            ['permission_id' => 55, 'role_id' => 39],  // supplier_items-delete
            ['permission_id' => 59, 'role_id' => 39],  // invoices-delete
            ['permission_id' => 63, 'role_id' => 39],  // invoice_exports-delete
            ['permission_id' => 67, 'role_id' => 39],  // custody-delete
            ['permission_id' => 71, 'role_id' => 39],  // currency-delete
            ['permission_id' => 75, 'role_id' => 39],  // employee-delete
            ['permission_id' => 91, 'role_id' => 39],  // sections-delete
            ['permission_id' => 95, 'role_id' => 39],  // stores-delete
            ['permission_id' => 124, 'role_id' => 39], // suppliers-delete
            // Fuel delete permissions
            ['permission_id' => 79, 'role_id' => 39],  // diesels-delete
            ['permission_id' => 83, 'role_id' => 39],  // dieselexport-delete
            ['permission_id' => 87, 'role_id' => 39],  // report-delete
            // Base access permissions
            ['permission_id' => 26, 'role_id' => 39],  // stock
            ['permission_id' => 12, 'role_id' => 39],  // diesels

        ];

        DB::table('role_has_permissions')->insertOrIgnore($assignments);

        $purchasePermissions = DB::table('permissions')
            ->whereIn('name', ['purchase', 'purchases-create', 'purchases-read', 'purchases-update', 'purchases-delete'])
            ->where('guard_name', 'web')
            ->pluck('id');

        $purchaseRoles = DB::table('roles')
            ->whereIn('name', ['admin', 'Super-User', 'purchases-manager', 'purchase', 'finance', 'tender_committee'])
            ->where('guard_name', 'web')
            ->pluck('id');

        foreach ($purchaseRoles as $roleId) {
            foreach ($purchasePermissions as $permissionId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            }
        }
    }
}