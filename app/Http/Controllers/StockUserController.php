<?php

namespace App\Http\Controllers;

use App\Models\StockUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Stock;

use Exception;

class StockUserController extends Controller
{

    public function index()
    {
        $relations = StockUser::with(['user', 'stock'])->get();

        // Transformer pour ne garder que les noms
        $result = $relations->map(function ($relation) {
            return [
                'user_name' => $relation->user->username,
                'stock_name' => $relation->stock->name,
            ];
        });

        return response()->json($result, 200);
    }

    public function attachStock(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'stock_id' => 'required|exists:stocks,id',
            'quantite' => 'required|integer|min:1', // nouveau champ
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $stockId = $request->stock_id;

            // Attacher avec la quantité dans la pivot
            $user->stocks()->syncWithoutDetaching([
                $stockId => ['quantite' => $request->quantite]
            ]);

            return response()->json([
                'message' => 'Stock attaché avec succès',
                'user_stocks' => $user->stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }

    public function updateStockQuantity(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'stock_id' => 'required|exists:stocks,id',
            'quantite' => 'required|integer|min:1',
        ]);

        try {
            $user = User::findOrFail($request->user_id);

            // Mettre à jour la quantité dans la table pivot
            $user->stocks()->updateExistingPivot($request->stock_id, [
                'quantite' => $request->quantite
            ]);

            return response()->json([
                'message' => 'Quantité mise à jour avec succès',
                'user_stocks' => $user->stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }

    // Détacher un stock d'un utilisateur
    public function detachStock(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'stock_id' => 'required|exists:stocks,id',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $user->stocks()->detach($request->stock_id);

            return response()->json([
                'message' => 'Stock détaché avec succès',
                'user_stocks' => $user->stocks
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }

    // Lister tous les stocks d'un utilisateur
    // public function listUserStocks($userId)
    // {
    //     try {
    //         $user = User::with('stocks')->findOrFail($userId);
    //         return response()->json($user->stocks, 200);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
    //     }
    // }
}
