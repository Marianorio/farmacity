<?php

// app/Http/Controllers/VistaAdminController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class VistaAdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.vista_admin', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'role' => 'required|in:Admin,Farmaceutico,Auxiliar,Cajero'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if (!$user->assignRole($request->role)) {
                throw new \Exception('No se pudo asignar el rol al usuario');
            }

            Log::info('Usuario y rol creados exitosamente', [
                'user_id' => $user->id,
                'role' => $request->role
            ]);
            
            return redirect()->route('vista_admin')->with('success', 'Usuario creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return redirect()->route('vista_admin')->with('error', 'Error al crear el usuario: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Prevenir la eliminación de usuarios administrativos críticos
            if (in_array($id, [1, 3, 4])) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden eliminar usuarios administrativos del sistema'
                ], 403);
            }
            
            $user = User::findOrFail($id);
            
            // Eliminar roles del usuario
            $user->roles()->detach();
            
            // Eliminar el usuario
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Manejo más seguro de la obtención del rol
            $role = null;
            if ($user->roles && $user->roles->first()) {
                $role = $user->roles->first()->name;
            }
            
            Log::info('Usuario encontrado para editar:', [
                'user_id' => $id,
                'role' => $role
            ]);

            return response()->json([
                'user' => $user,
                'role' => $role
            ]);

        } catch (\Exception $e) {
            Log::error('Error al editar usuario:', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos del usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Iniciando actualización de usuario:', ['id' => $id, 'data' => $request->all()]);
            
            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|in:Admin,Farmaceutico,Auxiliar,Cajero'
            ]);

            DB::beginTransaction();

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Asegurarse de que el rol se actualice correctamente
            $user->syncRoles([$request->role]);

            DB::commit();

            Log::info('Usuario y rol actualizados correctamente', [
                'user_id' => $user->id,
                'role' => $request->role
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar usuario: ' . $e->getMessage(), [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a user's password (admin action).
     */
    public function updatePassword(Request $request, $id)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::findOrFail($id);

            DB::beginTransaction();

            $user->password = Hash::make($request->password);
            $user->save();

            DB::commit();

            Log::info('Contraseña actualizada por admin', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Contraseña actualizada correctamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            return response()->json(['success' => false, 'message' => $ve->getMessage()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar contraseña: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar la contraseña'], 500);
        }
    }

    /**
     * Generate a temporary password, save hashed, and return plaintext once.
     * This is a secure alternative to showing stored passwords (which are hashed).
     */
    public function generateTemporaryPassword(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Generate a secure random password (12 chars)
            $plain = bin2hex(random_bytes(6)); // 12 hex chars

            DB::beginTransaction();
            $user->password = Hash::make($plain);
            $user->save();
            DB::commit();

            Log::info('Contraseña temporal generada por admin', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'password' => $plain,
                'message' => 'Contraseña temporal generada. Muéstrala al usuario y pídale que la cambie.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error generando contraseña temporal: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'No se pudo generar la contraseña temporal'], 500);
        }
    }
}
