<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Exception;

class StockController extends Controller
{
    // Récupérer tous les stocks avec leurs utilisateurs
    public function index()
    {
        try {
            $stocks = Stock::all();
            return response()->json($stocks, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la récupération des stocks',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Créer un stock (indépendant, sans attachement utilisateur)
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'description' => 'nullable|string',
                'status' => 'required|string',
            ]);

            $stock = Stock::create($data);

            return response()->json($stock, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création du stock',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Afficher un stock avec ses utilisateurs
    public function show($id)
    {
        try {
            $stock = Stock::with('users')->findOrFail($id);
            return response()->json($stock, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Stock non trouvé',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    // Mettre à jour un stock
    public function update(Request $request, $id)
    {
        try {
            $stock = Stock::findOrFail($id);

            $data = $request->validate([
                'name' => 'sometimes|string',
                'description' => 'sometimes|string',
                'status' => 'sometimes|string',
            ]);

            $stock->update($data);

            return response()->json($stock, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la mise à jour du stock',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Supprimer un stock
    public function destroy($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->delete();
            return response()->json(['message' => 'Stock supprimé avec succès'], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression du stock',
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
