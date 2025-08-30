<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Stock;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return response()->json($users, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des utilisateurs',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'username' => 'required|string',
                'email'    => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);

            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            return response()->json($user, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création de l\'utilisateur',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('stocks')->findOrFail($id);
            return response()->json($user, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Utilisateur non trouvé',
                'message' => $e->getMessage()
            ], 404);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $data = $request->validate([
                'username' => 'sometimes|string',
                'email'    => 'sometimes|email|unique:users,email,' . $id,
                'password' => 'sometimes|min:6',
                'adresse'  => 'sometimes|string',
            ]);

            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $user->update($data);
            return response()->json($user, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour de l\'utilisateur',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression de l\'utilisateur',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    
}
