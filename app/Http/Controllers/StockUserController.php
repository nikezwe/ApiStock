<?php

namespace App\Http\Controllers;

use App\Models\StockUser;
use Illuminate\Http\Request;

use Exception;

class StockUserController extends Controller
{
    public function index()
    {
        try {
            $relations = StockUser::with(['user', 'stock'])->get();
            return response()->json($relations, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des relations', 'message' => $e->getMessage()], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|exists:users,id',
                'stock_id' => 'required|exists:stocks,id',
            ]);

            $exists = StockUser::where('user_id', $data['user_id'])
                                ->where('stock_id', $data['stock_id'])
                                ->first();
            if ($exists) {
                return response()->json(['message' => 'Cette relation existe déjà'], 409);
            }

            $relation = StockUser::create($data);

            return response()->json($relation, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création de la relation', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $relation = StockUser::with(['user', 'stock'])->findOrFail($id);
            return response()->json($relation, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Relation non trouvée', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $relation = StockUser::findOrFail($id);

            $data = $request->validate([

            ]);

            $relation->update($data);

            return response()->json($relation, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour de la relation', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $relation = StockUser::findOrFail($id);
            $relation->delete();

            return response()->json(['message' => 'Relation supprimée avec succès'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de la relation', 'message' => $e->getMessage()], 500);
        }
    }
}
