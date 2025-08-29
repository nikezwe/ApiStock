<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthController extends Controller
{
    /**
     * Inscription d’un nouvel utilisateur
     */
    public function register(Request $request)
    {
        try {
            // Validation
            $data = $request->validate([
                'username' => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|string|',
            ]);

            // Création utilisateur
            $user = User::create([
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            // Création token
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'user'  => $user,
                'token' => $token
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Erreur lors de l’inscription',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Connexion utilisateur
     */
    public function login(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            // Vérification utilisateur
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Identifiants invalides'], 401);
            }

            // Supprimer les anciens tokens (optionnel)
            $user->tokens()->delete();

            // Création nouveau token
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'user'  => $user,
                'token' => $token
            ], 200);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Erreur lors de la connexion',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Déconnexion utilisateur
     */
    public function logout(Request $request)
    {
        try {
            // Supprimer le token utilisé pour cette requête
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Déconnecté avec succès'], 200);

        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Erreur lors de la déconnexion',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer le profil de l'utilisateur connecté
     */
    public function profile(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
