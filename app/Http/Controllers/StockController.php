<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Exception;

class StockController extends Controller
{
    public function index(Request $request)
    {
        try {
            $stocks = $request->user()->stocks()->with('users')->get();
            return response()->json($stocks, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des stocks', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'status' => 'required|string'
            ]);

            $data['user_id'] = $request->user()->id; // utilisateur connecté

            $stock = Stock::create($data);

            return response()->json($stock, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création du stock', 'message' => $e->getMessage()], 500);
        }
    }
    public function show(Request $request, $id)
    {
        try {
            $stock = $request->user()->stocks()->with('users')->findOrFail($id);
            return response()->json($stock, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Stock non trouvé ou non autorisé', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $stock = $request->user()->stocks()->findOrFail($id);

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

    public function destroy(Request $request, $id)
    {
        try {
            $stock = $request->user()->stocks()->findOrFail($id);
            $stock->delete();

            return response()->json(['message' => 'Stock supprimé avec succès'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression du stock', 'message' => $e->getMessage()], 500);
        }
    }
    public function attachUser(Request $request, $id)
    {
        try {
            $stock = $request->user()->stocks()->findOrFail($id);

            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);

            $stock->users()->attach($request->user_id);

            return response()->json(['message' => 'Utilisateur ajouté au stock'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'ajout de l\'utilisateur', 'message' => $e->getMessage()], 500);
        }
    }

    public function detachUser(Request $request, $id)
    {
        try {
            $stock = $request->user()->stocks()->findOrFail($id);

            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);

            $stock->users()->detach($request->user_id);

            return response()->json(['message' => 'Utilisateur retiré du stock'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors du retrait de l\'utilisateur', 'message' => $e->getMessage()], 500);
        }
    }
}
