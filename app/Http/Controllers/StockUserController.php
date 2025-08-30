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
        return response()->json($relations, 200);
    }

    public function attachStock(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'stock_id' => 'required|exists:stocks,id',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $stockId = $request->stock_id;

            // Attacher sans dupliquer
            $user->stocks()->syncWithoutDetaching([$stockId]);

            return response()->json([
                'message' => 'Stock attaché avec succès',
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
