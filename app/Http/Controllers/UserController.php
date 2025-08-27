<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with('stocks')->get();
            return response()->json($users, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des utilisateurs', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'username' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);

            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            return response()->json($user, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de l\'utilisateur', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('stocks')->findOrFail($id);
            return response()->json($user, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Utilisateur non trouvé', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
 
         $data = $request->validate([
                'username' => 'sometimes|string',
                'first_name' => 'sometimes|string',
                'last_name' => 'sometimes|string',
                'email' => 'email|unique:users,email,',
                'password' => 'sometimes|min:6',
                'adrresse' => 'sometimes|string',
            ]);
        try {
            $user = User::findOrFail($id);

            return response()->json($data, 200);

            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            $user->update($data);
            $user->save();
            return response()->json($user, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de l\'utilisateur', 'message' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de l\'utilisateur', 'message' => $e->getMessage()], 500);
        }
    }

    public function addStock(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'status' => 'required|string'
            ]);

            $data['user_id'] = $user->id;

            $stock = Stock::create($data);

            return response()->json($stock, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'ajout du stock', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStock(Request $request, $userId, $stockId)
    {
        try {
            $user = User::findOrFail($userId);
            $stock = $user->stocks()->findOrFail($stockId);

            $data = $request->validate([
                'name' => 'sometimes|string',
                'description' => 'sometimes|string',
                'status' => 'sometimes|string'
            ]);

            $stock->update($data);

            return response()->json($stock, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour du stock', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteStock($userId, $stockId)
    {
        try {
            $user = User::findOrFail($userId);
            $stock = $user->stocks()->findOrFail($stockId);

            $stock->delete();

            return response()->json(['message' => 'Stock supprimé avec succès'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression du stock', 'message' => $e->getMessage()], 500);
        }
    }
}
